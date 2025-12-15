<?php

namespace App\Jobs;

use App\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

/**
 * SendEmailNotification Job
 * Queued job for sending emails asynchronously
 * Handles email delivery and error tracking
 */
class SendEmailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Maximum retry attempts
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Timeout in seconds
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * Email recipient
     *
     * @var string
     */
    protected $email;

    /**
     * Email subject
     *
     * @var string
     */
    protected $subject;

    /**
     * Email view/template
     *
     * @var string
     */
    protected $view;

    /**
     * Email data
     *
     * @var array
     */
    protected $data;

    /**
     * Email type
     *
     * @var string
     */
    protected $emailType;

    /**
     * User ID (who initiated the email)
     *
     * @var int
     */
    protected $createdBy;

    /**
     * Related model type (polymorphic)
     *
     * @var string
     */
    protected $relatedType;

    /**
     * Related model ID (polymorphic)
     *
     * @var int
     */
    protected $relatedId;

    /**
     * Email log ID to update
     *
     * @var int
     */
    protected $emailLogId;

    /**
     * Create a new job instance.
     *
     * @param string $email
     * @param string $subject
     * @param string $view
     * @param array $data
     * @param string|null $emailType
     * @param int|null $createdBy
     * @param string|null $relatedType
     * @param int|null $relatedId
     * @param int|null $emailLogId
     */
    public function __construct(
        $email,
        $subject,
        $view,
        $data = [],
        $emailType = 'transactional',
        $createdBy = null,
        $relatedType = null,
        $relatedId = null,
        $emailLogId = null
    ) {
        $this->email = $email;
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
        $this->emailType = $emailType;
        $this->createdBy = $createdBy;
        $this->relatedType = $relatedType;
        $this->relatedId = $relatedId;
        $this->emailLogId = $emailLogId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('SendEmailNotification job started', [
                'email' => $this->email,
                'type' => $this->emailType,
            ]);

            // Create Mailable
            $mailable = new class($this->subject, $this->view, $this->data) extends \Illuminate\Mail\Mailable {
                public $subject;
                protected $emailView;
                protected $emailData;

                public function __construct($subject, $view, $data)
                {
                    $this->subject = $subject;
                    $this->emailView = $view;
                    $this->emailData = $data;
                }

                public function build()
                {
                    return $this->view($this->emailView, $this->emailData)
                        ->subject($this->subject);
                }
            };

            // Send email
            Mail::to($this->email)->send($mailable);

            // Update email log if exists
            if ($this->emailLogId) {
                $emailLog = EmailLog::find($this->emailLogId);
                if ($emailLog) {
                    $emailLog->update([
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);
                }
            } else {
                // Create email log
                EmailLog::create([
                    'email' => $this->email,
                    'subject' => $this->subject,
                    'email_type' => $this->emailType,
                    'status' => 'sent',
                    'created_by' => $this->createdBy,
                    'related_type' => $this->relatedType,
                    'related_id' => $this->relatedId,
                    'sent_at' => now(),
                ]);
            }

            Log::info('Email sent successfully', [
                'email' => $this->email,
                'subject' => $this->subject,
            ]);

        } catch (Exception $e) {
            Log::error('SendEmailNotification job error', [
                'email' => $this->email,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

            // Update email log with error
            if ($this->emailLogId) {
                $emailLog = EmailLog::find($this->emailLogId);
                if ($emailLog) {
                    $emailLog->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                }
            } else {
                // Create failed email log
                EmailLog::create([
                    'email' => $this->email,
                    'subject' => $this->subject,
                    'email_type' => $this->emailType,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'created_by' => $this->createdBy,
                    'related_type' => $this->relatedType,
                    'related_id' => $this->relatedId,
                ]);
            }

            // Retry on failure
            if ($this->attempts() < $this->tries) {
                $this->release(60); // Retry after 60 seconds
            } else {
                $this->fail($e);
            }
        }
    }

    /**
     * Handle a job failure.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Log::critical('SendEmailNotification job failed permanently', [
            'email' => $this->email,
            'subject' => $this->subject,
            'error' => $exception->getMessage(),
        ]);

        // Update email log as permanently failed
        if ($this->emailLogId) {
            $emailLog = EmailLog::find($this->emailLogId);
            if ($emailLog) {
                $emailLog->update([
                    'status' => 'failed',
                    'error_message' => 'Failed after ' . $this->tries . ' attempts: ' . $exception->getMessage(),
                ]);
            }
        }
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addMinutes(5);
    }
}
