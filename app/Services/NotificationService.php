<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Notification Service
 * Handles in-app database notifications for users
 * Used with Laravel's built-in notification system
 */
class NotificationService
{
    /**
     * Create an in-app notification
     * Stores notification in database for later retrieval
     *
     * @param int|User $user User ID or User model
     * @param string $title Notification title
     * @param string $message Notification message
     * @param string $type Notification type (appointment, payment, insurance, etc)
     * @param array $data Additional data to store with notification
     * @param string $notificationClass Notification class to use
     * @return DatabaseNotification
     */
    public function createNotification(
        $user,
        $title,
        $message,
        $type = 'system',
        $data = [],
        $notificationClass = null
    ) {
        try {
            // Get user instance
            if (is_numeric($user)) {
                $user = User::findOrFail($user);
            }

            // Build notification data
            $notificationData = array_merge($data, [
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'created_at' => now()->toDateTimeString(),
            ]);

            // Create generic notification using database driver
            $notification = new class($notificationData) {
                private $data;

                public function __construct($data)
                {
                    $this->data = $data;
                }

                public function toDatabase($notifiable)
                {
                    return $this->data;
                }
            };

            // Send notification (stores to database automatically)
            if ($notificationClass) {
                $user->notify(new $notificationClass($data));
            } else {
                $user->notify($notification);
            }

            Log::info('In-app notification created', [
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
            ]);

            return $user->notifications()->latest()->first();
        } catch (Exception $e) {
            Log::error('Failed to create notification', [
                'user_id' => $user->id ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Mark notification as read
     *
     * @param string $notificationId Notification ID (UUID)
     * @param int|User $user User ID or User model (optional, for validation)
     * @return bool
     */
    public function markAsRead($notificationId, $user = null)
    {
        try {
            $user = $user instanceof User ? $user : auth()->user();
            if (!$user) {
                return false;
            }

            $notification = $user->notifications()->findOrFail($notificationId);
            $notification->markAsRead();

            Log::info('Notification marked as read', [
                'user_id' => $user->id,
                'notification_id' => $notificationId,
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to mark notification as read', [
                'notification_id' => $notificationId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Mark all notifications as read for a user
     *
     * @param int|User $user User ID or User model
     * @return int Number of notifications marked as read
     */
    public function markAllAsRead($user = null)
    {
        try {
            $user = $user instanceof User ? $user : auth()->user();
            if (!$user) {
                return 0;
            }

            $count = 0;
            foreach ($user->unreadNotifications as $notification) {
                $notification->markAsRead();
                $count++;
            }

            Log::info('All notifications marked as read', [
                'user_id' => $user->id,
                'count' => $count,
            ]);

            return $count;
        } catch (Exception $e) {
            Log::error('Failed to mark all notifications as read', [
                'user_id' => $user->id ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            return 0;
        }
    }

    /**
     * Delete a notification
     *
     * @param string $notificationId Notification ID (UUID)
     * @param int|User $user User ID or User model (optional, for validation)
     * @return bool
     */
    public function deleteNotification($notificationId, $user = null)
    {
        try {
            $user = $user instanceof User ? $user : auth()->user();
            if (!$user) {
                return false;
            }

            $notification = $user->notifications()->findOrFail($notificationId);
            $notification->delete();

            Log::info('Notification deleted', [
                'user_id' => $user->id,
                'notification_id' => $notificationId,
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to delete notification', [
                'notification_id' => $notificationId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Delete all read notifications for a user
     *
     * @param int|User $user User ID or User model
     * @return int Number of notifications deleted
     */
    public function deleteAllRead($user = null)
    {
        try {
            $user = $user instanceof User ? $user : auth()->user();
            if (!$user) {
                return 0;
            }

            $count = $user->readNotifications()->delete();

            Log::info('Read notifications deleted', [
                'user_id' => $user->id,
                'count' => $count,
            ]);

            return $count;
        } catch (Exception $e) {
            Log::error('Failed to delete read notifications', [
                'user_id' => $user->id ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            return 0;
        }
    }

    /**
     * Get unread notification count
     *
     * @param int|User $user User ID or User model
     * @return int
     */
    public function getUnreadCount($user = null)
    {
        try {
            $user = $user instanceof User ? $user : auth()->user();
            if (!$user) {
                return 0;
            }

            return $user->unreadNotifications()->count();
        } catch (Exception $e) {
            Log::error('Failed to get unread count', [
                'user_id' => $user->id ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            return 0;
        }
    }

    /**
     * Get all notifications for a user
     *
     * @param int|User $user User ID or User model
     * @param int $limit Number of notifications to return
     * @param string $type Filter by type (optional)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNotifications($user = null, $limit = 50, $type = null)
    {
        try {
            $user = $user instanceof User ? $user : auth()->user();
            if (!$user) {
                return collect();
            }

            $query = $user->notifications()->latest();

            if ($type) {
                $query->where('type', 'like', "%{$type}%");
            }

            return $query->limit($limit)->get();
        } catch (Exception $e) {
            Log::error('Failed to get notifications', [
                'user_id' => $user->id ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Get unread notifications for a user
     *
     * @param int|User $user User ID or User model
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnreadNotifications($user = null, $limit = 50)
    {
        try {
            $user = $user instanceof User ? $user : auth()->user();
            if (!$user) {
                return collect();
            }

            return $user->unreadNotifications()
                ->latest()
                ->limit($limit)
                ->get();
        } catch (Exception $e) {
            Log::error('Failed to get unread notifications', [
                'user_id' => $user->id ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Get notifications by type
     *
     * @param string $type
     * @param int|User $user User ID or User model
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNotificationsByType($type, $user = null, $limit = 50)
    {
        try {
            $user = $user instanceof User ? $user : auth()->user();
            if (!$user) {
                return collect();
            }

            return $user->notifications()
                ->where('type', 'like', "%{$type}%")
                ->latest()
                ->limit($limit)
                ->get();
        } catch (Exception $e) {
            Log::error('Failed to get notifications by type', [
                'user_id' => $user->id ?? 'unknown',
                'type' => $type,
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    /**
     * Get notification by ID
     *
     * @param string $notificationId
     * @param int|User $user User ID or User model
     * @return DatabaseNotification|null
     */
    public function getNotification($notificationId, $user = null)
    {
        try {
            $user = $user instanceof User ? $user : auth()->user();
            if (!$user) {
                return null;
            }

            return $user->notifications()->find($notificationId);
        } catch (Exception $e) {
            Log::error('Failed to get notification', [
                'notification_id' => $notificationId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Clean up old notifications (older than specified days)
     *
     * @param int $daysOld Delete notifications older than this many days
     * @return int Number of notifications deleted
     */
    public function cleanupOldNotifications($daysOld = 30)
    {
        try {
            $cutoffDate = now()->subDays($daysOld);
            $count = DatabaseNotification::where('created_at', '<', $cutoffDate)->delete();

            Log::info('Old notifications cleaned up', [
                'days_old' => $daysOld,
                'count' => $count,
            ]);

            return $count;
        } catch (Exception $e) {
            Log::error('Failed to cleanup old notifications', [
                'error' => $e->getMessage(),
            ]);

            return 0;
        }
    }

    /**
     * Get notification statistics for a user
     *
     * @param int|User $user User ID or User model
     * @return array Statistics
     */
    public function getStatistics($user = null)
    {
        try {
            $user = $user instanceof User ? $user : auth()->user();
            if (!$user) {
                return [];
            }

            $allNotifications = $user->notifications()->count();
            $unreadNotifications = $user->unreadNotifications()->count();
            $readNotifications = $allNotifications - $unreadNotifications;

            // Count by type
            $byType = $user->notifications()
                ->select('type')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type');

            return [
                'total' => $allNotifications,
                'unread' => $unreadNotifications,
                'read' => $readNotifications,
                'by_type' => $byType,
                'unread_percentage' => $allNotifications > 0 ? round(($unreadNotifications / $allNotifications) * 100) : 0,
            ];
        } catch (Exception $e) {
            Log::error('Failed to get notification statistics', [
                'user_id' => $user->id ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Trigger notification event for listeners
     * Dispatches custom event with data
     *
     * @param string $eventType
     * @param array $data
     * @return void
     */
    public function triggerNotificationEvent($eventType, $data = [])
    {
        try {
            // Event will be dispatched by listeners in app/Listeners/
            // This method just logs the trigger
            Log::info('Notification event triggered', [
                'event' => $eventType,
                'data' => $data,
            ]);
        } catch (Exception $e) {
            Log::error('Failed to trigger notification event', [
                'event' => $eventType,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
