<?php

use Carbon\Carbon;

if (!function_exists('dateForHumans')) {
    /**
     * Format date to human readable format
     *
     * @param string|Carbon $date
     * @param string $format Options: 'relative', 'short', 'long', 'custom'
     * @param string|null $customFormat Custom format string (e.g., 'd/m/Y')
     * @return string|null
     */
    function dateForHumans($date, $format = 'relative', $customFormat = null)
    {
        if (!$date) {
            return null;
        }

        try {
            $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);

            return match ($format) {
                'relative' => $carbonDate->diffForHumans(),
                'short' => $carbonDate->format('d M Y'),
                'long' => $carbonDate->format('F j, Y'),
                'full' => $carbonDate->format('l, F j, Y'),
                'datetime' => $carbonDate->format('d M Y, h:i A'),
                'custom' => $carbonDate->format($customFormat ?? 'Y-m-d'),
                default => $carbonDate->diffForHumans()
            };
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('age')) {
    /**
     * Calculate age from birthdate
     *
     * @param string|Carbon $birthdate
     * @param bool $detailed Return detailed age (years, months, days) or just years
     * @return int|string|null
     */
    function age($birthdate, $detailed = false)
    {
        if (!$birthdate) {
            return null;
        }

        try {
            $dob = $birthdate instanceof Carbon ? $birthdate : Carbon::parse($birthdate);

            if ($detailed) {
                $diff = $dob->diff(Carbon::now());
                return $diff->y . ' years, ' . $diff->m . ' months, ' . $diff->d . ' days';
            }

            return $dob->age;
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('timeFromDate')) {
    /**
     * Extract time from datetime
     *
     * @param string|Carbon $datetime
     * @param string $format Options: '12h', '24h', 'short', 'custom'
     * @param string|null $customFormat Custom time format (e.g., 'H:i')
     * @return string|null
     */
    function timeFromDate($datetime, $format = '12h', $customFormat = null)
    {
        if (!$datetime) {
            return null;
        }

        try {
            $carbon = $datetime instanceof Carbon ? $datetime : Carbon::parse($datetime);

            return match ($format) {
                '12h' => $carbon->format('h:i A'),        // 02:30 PM
                '24h' => $carbon->format('H:i'),          // 14:30
                'short' => $carbon->format('h:i A'),      // 02:30 PM
                'full' => $carbon->format('h:i:s A'),     // 02:30:45 PM
                'seconds' => $carbon->format('H:i:s'),    // 14:30:45
                'custom' => $carbon->format($customFormat ?? 'H:i'),
                default => $carbon->format('h:i A')
            };
        } catch (\Exception $e) {
            return null;
        }
    }
}
