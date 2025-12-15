<?php

namespace App\Livewire\Notifications;

use App\Models\NotificationPreference;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * NotificationCenter Component
 * Displays user notifications with filtering and management
 */
class NotificationCenter extends Component
{
    use WithPagination;

    /**
     * Filter by notification type
     *
     * @var string
     */
    public $filter = 'all';

    /**
     * Search query
     *
     * @var string
     */
    public $search = '';

    /**
     * Notification service
     *
     * @var \App\Services\NotificationService
     */
    protected $notificationService;

    /**
     * Mount the component
     *
     * @return void
     */
    public function mount()
    {
        $this->notificationService = app('App\Services\NotificationService');
    }

    /**
     * Get notifications based on filters
     *
     * @return mixed
     */
    public function getNotifications()
    {
        $user = auth()->user();

        if (!$user) {
            return collect();
        }

        $query = $user->notifications();

        // Filter by type
        if ($this->filter !== 'all') {
            $query->where('type', 'like', "%{$this->filter}%");
        }

        // Filter by unread
        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($this->filter === 'read') {
            $query->whereNotNull('read_at');
        }

        // Search in data
        if ($this->search) {
            $query->where(function($q) {
                $q->where('data', 'like', "%{$this->search}%");
            });
        }

        return $query->latest()
            ->paginate(10);
    }

    /**
     * Get unread count
     *
     * @return int
     */
    public function getUnreadCount()
    {
        return auth()->user()?->unreadNotifications()->count() ?? 0;
    }

    /**
     * Mark notification as read
     *
     * @param string $id
     * @return void
     */
    public function markAsRead($id)
    {
        try {
            $this->notificationService->markAsRead($id, auth()->user());
            $this->dispatch('notificationRead', id: $id);
        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to mark notification as read');
        }
    }

    /**
     * Mark all as read
     *
     * @return void
     */
    public function markAllAsRead()
    {
        try {
            $this->notificationService->markAllAsRead(auth()->user());
            $this->dispatch('allNotificationsRead');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to mark all as read');
        }
    }

    /**
     * Delete notification
     *
     * @param string $id
     * @return void
     */
    public function deleteNotification($id)
    {
        try {
            $this->notificationService->deleteNotification($id, auth()->user());
            $this->dispatch('notificationDeleted', id: $id);
        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to delete notification');
        }
    }

    /**
     * Delete all read
     *
     * @return void
     */
    public function deleteAllRead()
    {
        try {
            $this->notificationService->deleteAllRead(auth()->user());
            $this->dispatch('allReadDeleted');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to delete read notifications');
        }
    }

    /**
     * Update filter
     *
     * @param string $filter
     * @return void
     */
    public function updateFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    /**
     * Render the component
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.notifications.notification-center', [
            'notifications' => $this->getNotifications(),
            'unreadCount' => $this->getUnreadCount(),
        ]);
    }
}
