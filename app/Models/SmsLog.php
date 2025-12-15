<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * SmsLog Model
 * Tracks all SMS sent through the system
 * Provides audit trail and delivery status
 */
class SmsLog extends Model
{
    use HasFactory;

    protected $table = 'sms_logs';

    protected $fillable = [
        'phone',
        'message',
        'message_type',
        'vodafone_message_id',
        'status',
        'response',
        'error_message',
        'transaction_price',
        'net_balance',
        'created_by',
        'related_type',
        'related_id',
    ];

    protected $casts = [
        'response' => 'json',
        'transaction_price' => 'decimal:4',
        'net_balance' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who created this SMS log
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
     * Scope: Get sent SMS
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope: Get failed SMS
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope: Get pending SMS
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get delivered SMS
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope: Filter by phone number
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $phone
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPhone($query, $phone)
    {
        return $query->where('phone', $phone);
    }

    /**
     * Scope: Filter by message type
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType($query, $type)
    {
        return $query->where('message_type', $type);
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
     * Scope: Get today's SMS
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope: Get this week's SMS
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
     * Scope: Get this month's SMS
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
     * Get status label
     *
     * @return string
     */
    public function getStatusLabel()
    {
        $labels = [
            'sent' => 'Sent',
            'failed' => 'Failed',
            'pending' => 'Pending',
            'delivered' => 'Delivered',
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
            'pending' => 'warning',
            'delivered' => 'info',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Check if SMS was sent successfully
     *
     * @return bool
     */
    public function isSent()
    {
        return $this->status === 'sent' || $this->status === 'delivered';
    }

    /**
     * Check if SMS failed
     *
     * @return bool
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if SMS is pending
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Get E-Push message ID
     *
     * @return string|null
     */
    public function getMessageId()
    {
        return $this->vodafone_message_id;
    }

    /**
     * Get formatted transaction price
     *
     * @return string
     */
    public function getFormattedPrice()
    {
        return $this->transaction_price ? number_format($this->transaction_price, 4) . ' EGP' : 'N/A';
    }

    /**
     * Get formatted balance
     *
     * @return string
     */
    public function getFormattedBalance()
    {
        return $this->net_balance ? number_format($this->net_balance, 2) . ' EGP' : 'N/A';
    }

    /**
     * Get SMS message preview (truncated)
     *
     * @param int $length
     * @return string
     */
    public function getMessagePreview($length = 50)
    {
        return strlen($this->message) > $length
            ? substr($this->message, 0, $length) . '...'
            : $this->message;
    }
}
