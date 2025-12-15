<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SmsLog;
use Exception;

/**
 * E-Push Egypt SMS API Client
 * Integrates with https://epushagency.eg/ API
 *
 * API Endpoint: https://api.epusheg.com/api/v2/send_bulk
 * Supports: Single SMS, Bulk SMS, Arabic & English
 */
class EPushApiClient
{
    protected $username;
    protected $password;
    protected $apiKey;
    protected $senderId;
    protected $endpoint;
    protected $timeout;
    protected $maxRetries;

    /**
     * Initialize E-Push API Client
     */
    public function __construct()
    {
        $config = config('services.epush');

        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->apiKey = $config['api_key'];
        $this->senderId = $config['sender_id'];
        $this->endpoint = $config['endpoint'];
        $this->timeout = $config['timeout'];
        $this->maxRetries = $config['max_retries'];

        if (!$this->username || !$this->password || !$this->apiKey) {
            throw new Exception('E-Push API credentials not configured. Check .env file.');
        }
    }

    /**
     * Send single SMS via E-Push API
     *
     * @param string $phone Phone number (format: 201XXXXXXXXX or 01XXXXXXXXX)
     * @param string $message SMS message (auto-detects Arabic/English)
     * @return array Response data with message_id and status
     * @throws Exception
     */
    public function send($phone, $message)
    {
        // Format phone number
        $phone = $this->formatPhoneNumber($phone);

        // Validate inputs
        if (!$this->validatePhoneNumber($phone)) {
            throw new Exception("Invalid phone number format: $phone");
        }

        if (empty($message)) {
            throw new Exception("Message cannot be empty");
        }

        // Check message length
        $maxLength = $this->detectLanguage($message) === 'ar' ? 402 : 918;
        if (strlen($message) > $maxLength) {
            throw new Exception("Message exceeds maximum length of $maxLength characters");
        }

        // Prepare request parameters
        $params = [
            'username' => $this->username,
            'password' => $this->password,
            'api_key' => $this->apiKey,
            'from' => $this->senderId,
            'to' => $phone,
            'message' => $message,
        ];

        // Send request with retry logic
        $response = $this->sendWithRetry($params);

        // Parse and validate response
        return $this->parseResponse($response, $phone, $message);
    }

    /**
     * Send bulk SMS to multiple recipients
     *
     * @param array $phones Array of phone numbers
     * @param string $message SMS message
     * @return array Results for each phone
     */
    public function sendBulk($phones, $message)
    {
        $results = [];

        foreach ($phones as $phone) {
            try {
                $results[$phone] = $this->send($phone, $message);
            } catch (Exception $e) {
                $results[$phone] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'message_id' => null,
                ];
            }
        }

        return $results;
    }

    /**
     * Send request with retry logic
     *
     * @param array $params Request parameters
     * @return array HTTP response
     */
    protected function sendWithRetry($params)
    {
        $attempts = 0;
        $lastException = null;

        while ($attempts < $this->maxRetries) {
            try {
                $response = Http::timeout($this->timeout)
                    ->get($this->endpoint, $params);

                return $response;
            } catch (Exception $e) {
                $attempts++;
                $lastException = $e;

                Log::warning("E-Push API retry {$attempts}/{$this->maxRetries}", [
                    'phone' => $params['to'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);

                // Wait before retry (exponential backoff)
                if ($attempts < $this->maxRetries) {
                    sleep(pow(2, $attempts - 1));
                }
            }
        }

        throw new Exception("E-Push API failed after {$this->maxRetries} attempts: " . $lastException->getMessage());
    }

    /**
     * Parse API response
     *
     * @param object $response HTTP response
     * @param string $phone Phone number
     * @param string $message Message text
     * @return array Parsed response
     */
    protected function parseResponse($response, $phone, $message)
    {
        try {
            if (!$response->successful()) {
                $error = $this->handleApiError($response);
                Log::error('E-Push API error', [
                    'phone' => $phone,
                    'status' => $response->status(),
                    'error' => $error,
                ]);

                return [
                    'success' => false,
                    'error' => $error,
                    'message_id' => null,
                    'status' => 'failed',
                ];
            }

            $data = $response->json();

            // Check if response contains error
            if (isset($data['error'])) {
                return [
                    'success' => false,
                    'error' => $data['error'],
                    'message_id' => null,
                    'status' => 'failed',
                ];
            }

            return [
                'success' => true,
                'message_id' => $data['new_msg_id'] ?? null,
                'transaction_price' => $data['transaction_price'] ?? 0,
                'net_balance' => $data['net_balance'] ?? 0,
                'status' => 'sent',
                'phone' => $phone,
                'message' => $message,
            ];
        } catch (Exception $e) {
            Log::error('E-Push response parsing error', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to parse API response: ' . $e->getMessage(),
                'message_id' => null,
                'status' => 'failed',
            ];
        }
    }

    /**
     * Handle API error responses
     *
     * @param object $response
     * @return string Error message
     */
    protected function handleApiError($response)
    {
        $status = $response->status();
        $body = $response->body();

        $errorMessages = [
            400 => 'Invalid request parameters',
            401 => 'Invalid username or password',
            403 => 'Invalid API key or insufficient permissions',
            404 => 'API endpoint not found',
            429 => 'Rate limit exceeded',
            500 => 'E-Push server error',
            503 => 'E-Push service unavailable',
        ];

        return $errorMessages[$status] ?? "Error {$status}: {$body}";
    }

    /**
     * Validate phone number format
     * Accepts: 201XXXXXXXXX or 01XXXXXXXXX
     *
     * @param string $phone
     * @return bool
     */
    protected function validatePhoneNumber($phone)
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Check if valid Egyptian number
        return preg_match('/^(201|20201|01)[0-9]{9}$/', $phone);
    }

    /**
     * Format phone number to E-Push format (201XXXXXXXXX)
     *
     * @param string $phone
     * @return string Formatted phone number
     */
    public function formatPhoneNumber($phone)
    {
        // Remove spaces and special characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert 01 to 201
        if (strpos($phone, '01') === 0) {
            $phone = '20' . $phone;
        }

        // Add country code if missing
        if (!preg_match('/^20/', $phone)) {
            $phone = '20' . $phone;
        }

        return $phone;
    }

    /**
     * Detect message language (Arabic or English)
     *
     * @param string $message
     * @return string 'ar' or 'en'
     */
    protected function detectLanguage($message)
    {
        // Check if message contains Arabic characters
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $message)) {
            return 'ar';
        }

        return 'en';
    }

    /**
     * Get SMS balance from E-Push account
     *
     * @return array Balance info
     */
    public function getBalance()
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->endpoint, [
                    'username' => $this->username,
                    'password' => $this->password,
                    'api_key' => $this->apiKey,
                    'action' => 'balance', // E-Push extension for getting balance
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'balance' => $data['net_balance'] ?? 0,
                    'currency' => 'EGP',
                    'last_checked' => now(),
                ];
            }

            return [
                'balance' => 0,
                'error' => 'Failed to retrieve balance',
            ];
        } catch (Exception $e) {
            Log::error('E-Push balance check failed', ['error' => $e->getMessage()]);
            return [
                'balance' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }
}
