<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * EmailLog Model
 * Tracks all emails sent through the system
 * Provides audit trail and delivery status
 */
class EmailLog extends Model
{
    use HasFactory;

    protected $table = 'email_logs';

    protected $fillable = [
        'email',
        'subject',
        'email_type',
        'status',
        'error_message',
        'provider_response',
        'created_by',
        'related_type',
        'related_id',
        'sent_at',
    ];

    protected $casts = [
        'provider_response' => 'json',
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this email log
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the related model (polymorphic)
     */
    public function related()
    {
        return $this->morphTo(__FUNCTION__, 'related_type', 'related_id');
    }

    /**
     * Scope: Get sent emails
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope: Get failed emails
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope: Get queued emails
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeQueued($query)
    {
        return $query->where('status', 'queued');
    }

    /**
     * Scope: Get bounced emails
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBounced($query)
    {
        return $query->where('status', 'bounced');
    }

    /**
     * Scope: Filter by email address
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $email
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Scope: Filter by email type
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType($query, $type)
    {
        return $query->where('email_type', $type);
    }

    /**
     * Scope: Filter by date range
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $from
     * @param \Carbon\Carbon $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetween($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Scope: Get today's emails
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope: Get this week's emails
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);
    }

    /**
     * Scope: Get this month's emails
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeThisMonth($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth(),
        ]);
    }

    /**
     * Scope: Get emails sent in last X hours
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $hours
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLastHours($query, $hours = 24)
    {
        return $query->where('created_at', '>', now()->subHours($hours));
    }

    /**
     * Get status label
     *
     * @return string
     */
    public function getStatusLabel()
    {
        $labels = [
            'sent' => 'Sent',
            'failed' => 'Failed',
            'queued' => 'Queued',
            'bounced' => 'Bounced',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get status badge color
     *
     * @return string
     */
    public function getStatusColor()
    {
        $colors = [
            'sent' => 'success',
            'failed' => 'danger',
            'queued' => 'warning',
            'bounced' => 'secondary',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get email type label
     *
     * @return string
     */
    public function getTypeLabel()
    {
        $types = [
            'welcome' => 'Welcome',
            'reminder' => 'Reminder',
            'appointment' => 'Appointment',
            'receipt' => 'Receipt',
            'insurance' => 'Insurance',
            'birthday' => 'Birthday',
            'holiday' => 'Holiday',
            'overdue' => 'Overdue Payment',
            'manual' => 'Manual',
            'other' => 'Other',
        ];

        return $types[$this->email_type] ?? ucfirst($this->email_type);
    }

    /**
     * Check if email was sent successfully
     *
     * @return bool
     */
    public function isSent()
    {
        return $this->status === 'sent';
    }

    /**
     * Check if email failed
     *
     * @return bool
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if email is queued
     *
     * @return bool
     */
    public function isQueued()
    {
        return $this->status === 'queued';
    }

    /**
     * Check if email bounced
     *
     * @return bool
     */
    public function isBounced()
    {
        return $this->status === 'bounced';
    }

    /**
     * Get email subject preview (truncated)
     *
     * @param int $length
     * @return string
     */
    public function getSubjectPreview($length = 40)
    {
        return strlen($this->subject) > $length
            ? substr($this->subject, 0, $length) . '...'
            : $this->subject;
    }

    /**
     * Get time taken to send (if sent)
     *
     * @return string|null
     */
    public function getDeliveryTime()
    {
        if (!$this->sent_at) {
            return null;
        }

        $seconds = $this->created_at->diffInSeconds($this->sent_at);

        if ($seconds < 60) {
            return "{$seconds}s";
        } elseif ($seconds < 3600) {
            return floor($seconds / 60) . "m";
        } else {
            return floor($seconds / 3600) . "h";
        }
    }

    /**
     * Get human-readable email type and status
     *
     * @return string
     */
    public function getSummary()
    {
        return "{$this->getTypeLabel()} - {$this->getStatusLabel()}";
    }
}
