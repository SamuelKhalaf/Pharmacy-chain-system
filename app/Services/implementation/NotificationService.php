<?php

namespace App\Services\implementation;

use App\Repositories\INotification;
use App\Services\INotificationService;
use App\Adapters\INotification as INotificationAdapter;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class NotificationService
 * Handles notification-related operations.
 */
class NotificationService implements INotificationService
{
    protected INotification $notificationRepository;
    protected INotificationAdapter $notificationAdapter;

    /**
     * NotificationService constructor.
     *
     * @param INotificationAdapter $notificationAdapter
     * @param INotification $notificationRepository
     */
    public function __construct(INotificationAdapter $notificationAdapter, INotification $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
        $this->notificationAdapter = $notificationAdapter;
    }

    /**
     * Retrieve all notifications.
     *
     * @return Collection
     */
    public function getAllNotifications(): Collection
    {
        $notifications = $this->notificationRepository->getAll();
        foreach ($notifications as $notification) {
            $notification->data = json_decode($notification->data, true);
        }
        return $notifications;
    }

    /**
     * Retrieve unread notifications with formatted data.
     *
     * @return array
     */
    public function getUnReadNotification(): array
    {
        $notifications = $this->notificationRepository->getUnReadNotification();
        $updatedData = [];
        foreach ($notifications as $notification) {
            $data = json_decode($notification->data, true);
            $updatedData[] = [
                'id' => $notification->id,
                'text' => $data['text'],
                'created_at' => $notification->created_at->diffForHumans(),
            ];
        }
        return $updatedData;
    }

    /**
     * Retrieve a specific notification by ID.
     *
     * @param int $id
     * @return object|null
     */
    public function getOneNotification(int $id): ?object
    {
        $notification = $this->notificationRepository->findById($id);
        $notification->data = json_decode($notification->data, true);
        return $notification;
    }

    /**
     * Create a new notification.
     *
     * @param array $data
     * @return void
     */
    public function createNotification(array $data): void
    {
        $this->notificationRepository->create($data);
    }

    /**
     * Mark a notification as read.
     *
     * @param int $id
     * @return void
     */
    public function markAsRead(int $id): void
    {
        $data = ['is_read' => true];
        $this->notificationRepository->update($data, $id);
    }

    /**
     * Notify super admins about critical stock levels.
     *
     * @param array $super_admins
     * @param array $critical_products
     * @return void
     */
    public function notifyAdminOfCriticalStock(array $super_admins, array $critical_products): void
    {
        foreach ($super_admins as $super_admin) {
            foreach ($critical_products as $product) {
                $this->notificationAdapter->send([
                    'admin_id' => $super_admin->id,
                    'data' => json_encode([
                        'text' => "Product reached critical level.",
                        'product_name' => $product->product_name,
                        'branch_name' => $product->branch_name,
                        'product_quantity' => $product->quantity,
                        'critical_level' => $product->critical_level
                    ]),
                    'is_read' => false,
                ]);
            }
        }
    }
}
