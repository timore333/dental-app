<?php

namespace App\Http\Controllers;

use App\Models\NotificationPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * NotificationController
 * Handles notification-related HTTP requests
 */
class NotificationController extends Controller
{
    /**
     * Show notification page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $user = auth()->user();
            $notificationsCount = $user->notifications()->count();
            $unreadCount = $user->unreadNotifications()->count();

            return view('notifications.index', [
                'notificationsCount' => $notificationsCount,
                'unreadCount' => $unreadCount,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load notifications page', [
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Failed to load notifications');
        }
    }

    /**
     * Get notification count (AJAX)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCount()
    {
        try {
            $user = auth()->user();
            $unreadCount = $user->unreadNotifications()->count();

            return response()->json([
                'success' => true,
                'unreadCount' => $unreadCount,
                'totalCount' => $user->notifications()->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get count',
            ], 500);
        }
    }

    /**
     * Get notifications (AJAX)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(Request $request)
    {
        try {
            $user = auth()->user();
            $limit = $request->get('limit', 5);

            $notifications = $user->unreadNotifications()
                ->latest()
                ->limit($limit)
                ->get()
                ->map(function($notif) {
                    return [
                        'id' => $notif->id,
                        'type' => $notif->type,
                        'title' => $notif->data['title'] ?? 'Notification',
                        'message' => $notif->data['message'] ?? '',
                        'read_at' => $notif->read_at,
                        'created_at' => $notif->created_at->diffForHumans(),
                    ];
                });

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'count' => $notifications->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get notifications',
            ], 500);
        }
    }

    /**
     * Mark notification as read
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request)
    {
        try {
            $request->validate([
                'notification_id' => 'required|string',
            ]);

            $user = auth()->user();
            $notification = $user->notifications()
                ->where('id', $request->notification_id)
                ->firstOrFail();

            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Marked as read',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to mark notification as read', [
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to mark as read',
            ], 500);
        }
    }

    /**
     * Mark all as read
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        try {
            $user = auth()->user();
            $user->unreadNotifications()->update(['read_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'All marked as read',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to mark all as read',
            ], 500);
        }
    }

    /**
     * Delete notification
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            $request->validate([
                'notification_id' => 'required|string',
            ]);

            $user = auth()->user();
            $user->notifications()
                ->where('id', $request->notification_id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete notification',
            ], 500);
        }
    }

    /**
     * Delete all read notifications
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAllRead()
    {
        try {
            $user = auth()->user();
            $user->notifications()
                ->whereNotNull('read_at')
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'All read notifications deleted',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete',
            ], 500);
        }
    }

    /**
     * Get notification preferences
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreferences()
    {
        try {
            $user = auth()->user();
            $preferences = $user->notificationPreferences ?? NotificationPreference::create([
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'preferences' => $preferences,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get preferences',
            ], 500);
        }
    }

    /**
     * Update notification preferences
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePreferences(Request $request)
    {
        try {
            $user = auth()->user();
            $preferences = $user->notificationPreferences;

            $preferences->update($request->only([
                'sms_enabled',
                'email_enabled',
                'in_app_enabled',
                'appointment_reminders',
                'payment_notifications',
                'insurance_notifications',
                'promotional_notifications',
                'marketing_sms',
                'quiet_hours_start',
                'quiet_hours_end',
                'email_frequency',
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Preferences updated',
                'preferences' => $preferences,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update preferences', [
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Failed to update preferences',
            ], 500);
        }
    }
}
