<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\SmsService;
use App\Models\SmsRateLimit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * SendSmsNotification Job
 * Queued job for sending SMS messages asynchronously
 * Handles rate limiting and error handling
 */
class SendSmsNotification implements ShouldQueue
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
     * SMS recipient phone number
     *
     * @var string
     */
    protected $phone;

    /**
     * SMS message text
     *
     * @var string
     */
    protected $message;

    /**
     * SMS message type
     *
     * @var string
     */
    protected $messageType;

    /**
     * User ID (who initiated the SMS)
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
     * Create a new job instance.
     *
     * @param string $phone
     * @param string $message
     * @param string $messageType
     * @param int|null $createdBy
     * @param string|null $relatedType
     * @param int|null $relatedId
     */
    public function __construct(
        $phone,
        $message,
        $messageType = 'transactional',
        $createdBy = null,
        $relatedType = null,
        $relatedId = null
    ) {
        $this->phone = $phone;
        $this->message = $message;
        $this->messageType = $messageType;
        $this->createdBy = $createdBy;
        $this->relatedType = $relatedType;
        $this->relatedId = $relatedId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('SendSmsNotification job started', [
                'phone' => $this->phone,
                'type' => $this->messageType,
            ]);

            // Check rate limiting
            if (!$this->checkRateLimit()) {
                Log::warning('SMS rate limited', [
                    'phone' => $this->phone,
                ]);
                $this->fail(new Exception('Rate limited'));
                return;
            }

            // Get SMS service
            $smsService = app('App\Services\SmsService');

            // Send SMS
            $result = $smsService->sendSms(
                $this->phone,
                $this->message,
                $this->messageType,
                $this->createdBy,
                $this->relatedType,
                $this->relatedId
            );

            if (!$result['success']) {
                Log::error('Failed to send SMS', [
                    'phone' => $this->phone,
                    'error' => $result['error'] ?? 'Unknown error',
                ]);
                throw new Exception($result['error'] ?? 'SMS sending failed');
            }

            // Update rate limit
            $rateLimit = SmsRateLimit::byPhone($this->phone)->first();
            if ($rateLimit) {
                $rateLimit->incrementCount();
            }

            Log::info('SMS sent successfully', [
                'phone' => $this->phone,
                'message_id' => $result['message_id'] ?? null,
            ]);

        } catch (Exception $e) {
            Log::error('SendSmsNotification job error', [
                'phone' => $this->phone,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

            // Retry on failure (automatic via ShouldQueue)
            if ($this->attempts() < $this->tries) {
                $this->release(60); // Retry after 60 seconds
            } else {
                $this->fail($e);
            }
        }
    }

    /**
     * Check if SMS should be rate limited
     *
     * @return bool
     */
    protected function checkRateLimit()
    {
        $maxPerDay = config('services.sms.rate_limit.max_per_phone_per_day', 10);
        $maxPerHour = config('services.sms.rate_limit.max_per_phone_per_hour', 3);

        $rateLimit = SmsRateLimit::byPhone($this->phone)->first();

        if (!$rateLimit) {
            // Create new rate limit record
            SmsRateLimit::create([
                'phone' => $this->phone,
                'count_today' => 0,
                'count_this_hour' => 0,
                'is_limited' => false,
            ]);
            return true;
        }

        // Check if limited
        if ($rateLimit->hasExceededDaily($maxPerDay) || $rateLimit->hasExceededHourly($maxPerHour)) {
            return false;
        }

        return true;
    }

    /**
     * Handle a job failure.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Log::critical('SendSmsNotification job failed permanently', [
            'phone' => $this->phone,
            'message' => $this->message,
            'error' => $exception->getMessage(),
        ]);
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
