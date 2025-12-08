<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Log an action to the audit logs table.
     */
    public function log(
        string $action,
        string $description = null,
        string $modelType = null,
        int $modelId = null,
        array $oldValues = null,
        array $newValues = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $this->getIpAddress(),
            'user_agent' => $this->getUserAgent(),
        ]);
    }

    /**
     * Get the client IP address.
     */
    public function getIpAddress(): string
    {
        // Check for shared internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        // Check for IP passed from proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        // Default
        else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }

        // Validate IP
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }

        // In case of multiple IPs (comma-separated), get the first one
        $ips = explode(',', $ip);
        $ip = trim($ips[0]);

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }

        return '0.0.0.0';
    }

    /**
     * Get the user agent string.
     */
    public function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }

    /**
     * Get recent audit logs.
     */
    public function getRecentLogs(int $limit = 50, int $days = 7)
    {
        return AuditLog::recent($days)
            ->with('user')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs for a specific user.
     */
    public function getUserLogs(int $userId, int $limit = 50)
    {
        return AuditLog::byUser($userId)
            ->with('user')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs for a specific action.
     */
    public function getActionLogs(string $action, int $limit = 50)
    {
        return AuditLog::byAction($action)
            ->with('user')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
