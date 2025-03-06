<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\INotificationService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    protected INotificationService $notificationService;

    /**
     * @param INotificationService $notificationService
     */
    public function __construct(INotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the notifications for the authenticated super admin.
     *
     * @return Factory|Application|View
     */
    public function index(): Factory|Application|View
    {
        $notifications = $this->notificationService->getAllNotifications();
        return view('admin.notification.index', compact('notifications'));
    }

    /**
     * Display the specified notification.
     *
     * @param int $id
     * @return Factory|Application|View
     */
    public function show(int $id): Factory|Application|View
    {
        $notification = $this->notificationService->getOneNotification($id);
        return view('admin.notification.view', compact('notification'));
    }

    /**
     * Get all unread notifications for the authenticated super admin.
     *
     * @return JsonResponse
     */
    public function getUnReadNotification(): JsonResponse
    {
        $notifications = $this->notificationService->getUnReadNotification();
        return response()->json($notifications);
    }

    /**
     * Mark the specified notification as read.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function markAsRead(int $id): RedirectResponse
    {
        $notification = $this->notificationService->getOneNotification($id);

        if (!$notification) {
            return redirect()->route('notification.index')->with(['error' => 'Notification not found']);
        }

        $this->notificationService->markAsRead($id);

        return redirect()->route('notification.index')->with(['success' => 'Notification marked as read']);
    }
}
