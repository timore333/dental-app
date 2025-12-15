<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * SmsRateLimit Model
 * Tracks SMS rate limiting for phone numbers
 * Prevents SMS spam by enforcing limits per phone per day/hour
 */
class SmsRateLimit extends Model
{
    use HasFactory;

    protected $table = 'sms_rate_limits';

    protected $fillable = [
        'phone',
        'count_today',
        'count_this_hour',
        'last_sms_at',
        'reset_at',
        'is_limited',
        'reason',
    ];

    protected $casts = [
        'last_sms_at' => 'datetime',
        'reset_at' => 'datetime',
        'is_limited' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope: Find by phone number
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
     * Scope: Get limited phones
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLimited($query)
    {
        return $query->where('is_limited', true);
    }

    /**
     * Scope: Get unlimited phones
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnlimited($query)
    {
        return $query->where('is_limited', false);
    }

    /**
     * Scope: Get phones with exceeded daily limit
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $maxPerDay
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExceededDaily($query, $maxPerDay = 10)
    {
        return $query->where('count_today', '>=', $maxPerDay);
    }

    /**
     * Scope: Get phones with exceeded hourly limit
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $maxPerHour
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExceededHourly($query, $maxPerHour = 3)
    {
        return $query->where('count_this_hour', '>=', $maxPerHour);
    }

    /**
     * Get limit status as formatted string
     *
     * @return string
     */
    public function getLimitStatus()
    {
        if (!$this->is_limited) {
            return 'OK';
        }

        return $this->reason ?? 'Limited';
    }

    /**
     * Get remaining SMS today
     *
     * @param int $maxPerDay
     * @return int
     */
    public function getRemainingToday($maxPerDay = 10)
    {
        $remaining = $maxPerDay - $this->count_today;
        return max(0, $remaining);
    }

    /**
     * Get remaining SMS this hour
     *
     * @param int $maxPerHour
     * @return int
     */
    public function getRemainingThisHour($maxPerHour = 3)
    {
        $remaining = $maxPerHour - $this->count_this_hour;
        return max(0, $remaining);
    }

    /**
     * Check if limit exceeded for today
     *
     * @param int $maxPerDay
     * @return bool
     */
    public function hasExceededDaily($maxPerDay = 10)
    {
        return $this->count_today >= $maxPerDay;
    }

    /**
     * Check if limit exceeded for this hour
     *
     * @param int $maxPerHour
     * @return bool
     */
    public function hasExceededHourly($maxPerHour = 3)
    {
        return $this->count_this_hour >= $maxPerHour;
    }

    /**
     * Check if rate limit is currently active
     *
     * @return bool
     */
    public function isLimited()
    {
        return $this->is_limited === true;
    }

    /**
     * Check if phone should be rate limited
     *
     * @param int $maxPerDay
     * @param int $maxPerHour
     * @return bool
     */
    public function shouldBeLimited($maxPerDay = 10, $maxPerHour = 3)
    {
        return $this->hasExceededDaily($maxPerDay) || $this->hasExceededHourly($maxPerHour);
    }

    /**
     * Increment SMS count (called when SMS is sent)
     *
     * @param int $maxPerDay
     * @param int $maxPerHour
     * @return void
     */
    public function incrementCount($maxPerDay = 10, $maxPerHour = 3)
    {
        $this->count_today++;
        $this->count_this_hour++;
        $this->last_sms_at = now();

        // Check if should be limited
        if ($this->shouldBeLimited($maxPerDay, $maxPerHour)) {
            $this->is_limited = true;

            if ($this->hasExceededDaily($maxPerDay)) {
                $this->reason = "Exceeded daily limit ({$maxPerDay} SMS/day)";
                $this->reset_at = now()->addDay();
            } elseif ($this->hasExceededHourly($maxPerHour)) {
                $this->reason = "Exceeded hourly limit ({$maxPerHour} SMS/hour)";
                $this->reset_at = now()->addHour();
            }
        }

        $this->save();
    }

    /**
     * Reset hourly count
     * Called by a scheduled job or manually
     *
     * @return void
     */
    public function resetHourlyCount()
    {
        $this->count_this_hour = 0;

        // If only hourly limit was exceeded, remove limited flag
        $maxPerDay = config('services.sms.rate_limit.max_per_phone_per_day', 10);
        if (!$this->hasExceededDaily($maxPerDay)) {
            $this->is_limited = false;
            $this->reason = null;
            $this->reset_at = null;
        }

        $this->save();
    }

    /**
     * Reset daily count
     * Called by a scheduled job or manually
     *
     * @return void
     */
    public function resetDailyCount()
    {
        $this->count_today = 0;
        $this->count_this_hour = 0;
        $this->is_limited = false;
        $this->reason = null;
        $this->reset_at = null;

        $this->save();
    }

    /**
     * Manually lift limit on a phone
     *
     * @return void
     */
    public function liftLimit()
    {
        $this->is_limited = false;
        $this->reason = null;
        $this->reset_at = null;
        $this->save();
    }

    /**
     * Manually apply limit to a phone
     *
     * @param string $reason Reason for limiting
     * @param \Carbon\Carbon $until When limit should be lifted
     * @return void
     */
    public function applyLimit($reason = 'Manual', $until = null)
    {
        $this->is_limited = true;
        $this->reason = $reason;
        $this->reset_at = $until ?? now()->addHours(24);
        $this->save();
    }

    /**
     * Check if reset time has passed
     *
     * @return bool
     */
    public function hasResetTimePasssed()
    {
        if (!$this->reset_at) {
            return false;
        }

        return now()->isAfter($this->reset_at);
    }

    /**
     * Get time until limit resets
     *
     * @return string|null
     */
    public function getTimeUntilReset()
    {
        if (!$this->reset_at) {
            return null;
        }

        $minutes = now()->diffInMinutes($this->reset_at, false);

        if ($minutes <= 0) {
            return 'Now';
        } elseif ($minutes < 60) {
            return "{$minutes}m";
        } else {
            $hours = ceil($minutes / 60);
            return "{$hours}h";
        }
    }

    /**
     * Get formatted limit reason
     *
     * @return string
     */
    public function getReasonLabel()
    {
        return $this->reason ?? 'No limit';
    }

    /**
     * Get limit status as badge color
     *
     * @return string
     */
    public function getStatusColor()
    {
        if (!$this->is_limited) {
            return 'success';
        }

        return 'danger';
    }

    /**
     * Get summary of current limits
     *
     * @param int $maxPerDay
     * @param int $maxPerHour
     * @return array
     */
    public function getSummary($maxPerDay = 10, $maxPerHour = 3)
    {
        return [
            'phone' => $this->phone,
            'is_limited' => $this->is_limited,
            'count_today' => $this->count_today,
            'count_this_hour' => $this->count_this_hour,
            'remaining_today' => $this->getRemainingToday($maxPerDay),
            'remaining_hour' => $this->getRemainingThisHour($maxPerHour),
            'last_sms_at' => $this->last_sms_at?->diffForHumans(),
            'reset_at' => $this->reset_at?->diffForHumans(),
            'reason' => $this->reason,
        ];
    }
}
