<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Livewire\Attributes\Reactive;
use App\Models\User;

class NotificationPanel extends Component
{
    #[Reactive]
    public $unreadCount = 0;

    public $notifications = [];
    public $filterType = 'all';
    public $isOpen = false;

    public function mount()
    {
        $this->loadNotifications();
        $this->updateUnreadCount();
    }

    public function loadNotifications()
    {
        $user = auth()->user();
        $query = $user->notifications();

        if ($this->filterType !== 'all') {
            $query->where('type', 'like', "%{$this->filterType}%");
        }

        $this->notifications = $query
            ->latest()
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function updateUnreadCount()
    {
        $this->unreadCount = auth()->user()
            ->unreadNotifications
            ->count();
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($notificationId);

        $notification->markAsRead();
        $this->loadNotifications();
        $this->updateUnreadCount();

        $this->dispatch('notification-read', id: $notificationId);
    }

    public function markAllAsRead()
    {
        auth()->user()
            ->unreadNotifications
            ->each->markAsRead();

        $this->loadNotifications();
        $this->updateUnreadCount();
    }

    public function delete($notificationId)
    {
        auth()->user()
            ->notifications()
            ->findOrFail($notificationId)
            ->delete();

        $this->loadNotifications();
        $this->updateUnreadCount();
    }

    public function filterByType($type)
    {
        $this->filterType = $type;
        $this->loadNotifications();
    }

    public function togglePanel()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function render()
    {
        return view('livewire.notifications.panel');
    }
}
