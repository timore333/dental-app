<?php

namespace App\Services;

use App\Models\SmsLog;
use App\Models\SmsRateLimit;
use App\Jobs\SendSmsNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * SMS Service
 * Handles all SMS operations including sending, queueing, rate limiting
 */
class SmsService
{
    protected $apiClient;

    public function __construct(EPushApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Send SMS immediately or queue for later
     *
     * @param string $phone Phone number (format: 201XXXXXXXXX or 01XXXXXXXXX)
     * @param string $message SMS message content
     * @param string $type Message type (appointment, payment, welcome, etc)
     * @param bool $queued If true, queue the job; if false, send immediately
     * @param int $delaySeconds Delay in seconds before sending
     * @return SmsLog
     */
    public function sendSms($phone, $message, $type = 'notification', $queued = true, $delaySeconds = 0)
    {
        try {
            // Format and validate phone
            $phone = $this->formatPhoneNumber($phone);
            if (!$this->verifySendSms($phone)) {
                throw new Exception("Invalid phone number: $phone");
            }

            // Check rate limiting
            if (!$this->checkRateLimit($phone)) {
                throw new Exception("Rate limit exceeded for phone: $phone");
            }

            // Create log entry
            $log = SmsLog::create([
                'phone' => $phone,
                'message' => $message,
                'message_type' => $type,
                'status' => $queued ? 'pending' : 'sent',
                'created_by' => auth()->id(),
            ]);

            if ($queued) {
                // Queue the job
                SendSmsNotification::dispatch($phone, $message, $type, $log->id)
                    ->delay(now()->addSeconds($delaySeconds));
            } else {
                // Send immediately
                $this->sendNow($phone, $message, $type, $log->id);
            }

            return $log;
        } catch (Exception $e) {
            Log::error('SMS send failed', [
                'phone' => $phone ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Send SMS immediately (synchronously)
     *
     * @param string $phone
     * @param string $message
     * @param string $type
     * @param int $logId
     * @return bool
     */
    public function sendNow($phone, $message, $type = 'notification', $logId = null)
    {
        try {
            $response = $this->apiClient->send($phone, $message);

            // Update log
            if ($logId) {
                $log = SmsLog::find($logId);
                if ($log) {
                    $log->update([
                        'status' => $response['success'] ? 'sent' : 'failed',
                        'vodafone_message_id' => $response['message_id'] ?? null,
                        'response' => $response,
                        'transaction_price' => $response['transaction_price'] ?? null,
                        'net_balance' => $response['net_balance'] ?? null,
                        'error_message' => $response['error'] ?? null,
                    ]);
                }
            }

            if ($response['success']) {
                // Update rate limit counter
                $this->updateRateLimitCounter($phone);
                Log::info('SMS sent successfully', ['phone' => $phone, 'type' => $type]);
                return true;
            } else {
                Log::error('SMS send failed', ['phone' => $phone, 'error' => $response['error'] ?? 'Unknown']);
                return false;
            }
        } catch (Exception $e) {
            Log::error('SMS send exception', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            if ($logId) {
                $log = SmsLog::find($logId);
                if ($log) {
                    $log->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }

            return false;
        }
    }

    /**
     * Send bulk SMS to multiple recipients
     *
     * @param array $phones Array of phone numbers
     * @param string $message
     * @param string $type
     * @return array Results
     */
    public function sendBulkSms($phones, $message, $type = 'notification')
    {
        $results = [];

        foreach ($phones as $phone) {
            try {
                $log = $this->sendSms($phone, $message, $type, queued: true);
                $results[$phone] = ['success' => true, 'log_id' => $log->id];
            } catch (Exception $e) {
                $results[$phone] = ['success' => false, 'error' => $e->getMessage()];
            }
        }

        return $results;
    }

    /**
     * Queue SMS for later sending
     *
     * @param string $phone
     * @param string $message
     * @param int $delaySeconds
     * @return SmsLog
     */
    public function queueSms($phone, $message, $delaySeconds = 0, $type = 'notification')
    {
        return $this->sendSms($phone, $message, $type, queued: true, delaySeconds: $delaySeconds);
    }

    /**
     * Verify phone number format
     *
     * @param string $phone
     * @return bool
     */
    public function verifySendSms($phone)
    {
        // Validate Egyptian phone format
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return preg_match('/^(201|20201|01)[0-9]{9}$/', $phone);
    }

    /**
     * Format phone number to standard format (201XXXXXXXXX)
     *
     * @param string $phone
     * @return string
     */
    public function formatPhoneNumber($phone)
    {
        return $this->apiClient->formatPhoneNumber($phone);
    }

    /**
     * Check if phone is rate-limited
     * Returns false if limit exceeded
     *
     * @param string $phone
     * @return bool
     */
    protected function checkRateLimit($phone)
    {
        $maxPerDay = config('services.sms.rate_limit.max_per_phone_per_day', 10);
        $maxPerHour = config('services.sms.rate_limit.max_per_phone_per_hour', 3);

        // Get count from cache (more efficient than DB)
        $countToday = Cache::get("sms_count_today_{$phone}", 0);
        $countThisHour = Cache::get("sms_count_hour_{$phone}", 0);

        if ($countToday >= $maxPerDay || $countThisHour >= $maxPerHour) {
            Log::warning('SMS rate limit exceeded', [
                'phone' => $phone,
                'count_today' => $countToday,
                'count_hour' => $countThisHour,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Update rate limit counters
     *
     * @param string $phone
     */
    protected function updateRateLimitCounter($phone)
    {
        // Increment daily counter (expires at midnight)
        $todayCount = Cache::get("sms_count_today_{$phone}", 0) + 1;
        Cache::put("sms_count_today_{$phone}", $todayCount, now()->addDay());

        // Increment hourly counter (expires in 1 hour)
        $hourCount = Cache::get("sms_count_hour_{$phone}", 0) + 1;
        Cache::put("sms_count_hour_{$phone}", $hourCount, now()->addHour());
    }

    /**
     * Log SMS attempt to audit trail
     *
     * @param string $phone
     * @param string $message
     * @param string $status
     * @param array $response
     * @return SmsLog
     */
    public function logSmsAttempt($phone, $message, $status, $response)
    {
        return SmsLog::create([
            'phone' => $phone,
            'message' => $message,
            'status' => $status,
            'response' => $response,
            'vodafone_message_id' => $response['message_id'] ?? null,
            'error_message' => $response['error'] ?? null,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Get SMS balance from E-Push
     *
     * @return array
     */
    public function getBalance()
    {
        return $this->apiClient->getBalance();
    }

    /**
     * Get SMS logs for a phone number
     *
     * @param string $phone
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLogsForPhone($phone, $limit = 50)
    {
        return SmsLog::where('phone', $phone)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get failed SMS for retry
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFailedSms($limit = 10)
    {
        return SmsLog::where('status', 'failed')
            ->where('created_at', '>', now()->subHours(24))
            ->oldest()
            ->limit($limit)
            ->get();
    }
}
