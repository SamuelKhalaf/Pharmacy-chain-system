<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\INotificationService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected INotificationService $notificationService;

    public function __construct(INotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(): Factory|Application|View
    {
        // get all notifications for specific authenticated super admin only
        $notifications = $this->notificationService->getAllNotifications();
        return view('admin.notification.index',compact('notifications'));
    }

    public function show($id): Factory|Application|View
    {
        $notification = $this->notificationService->getOneNotification($id);
        return view('admin.notification.view',compact('notification'));
    }
    public function getUnReadNotification(): JsonResponse
    {
        $notifications = $this->notificationService->getUnReadNotification();
        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = $this->notificationService->getOneNotification($id);

        if (!$notification) {
            return redirect()->route('notification.index')->with(['error' => 'Notification not found']);
        }

        $this->notificationService->markAsRead($id);

        return redirect()->route('notification.index')->with(['success' => 'Notification marked as read']);
    }
}
