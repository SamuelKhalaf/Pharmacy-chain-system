<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface INotificationService
 * Defines the contract for notification-related services.
 */
interface INotificationService
{
    /**
     * Retrieve all notifications.
     *
     * @return Collection
     */
    public function getAllNotifications(): Collection;

    /**
     * Retrieve unread notifications with formatted data.
     *
     * @return array
     */
    public function getUnReadNotification(): array;

    /**
     * Retrieve a specific notification by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getOneNotification(int $id): mixed;

    /**
     * Create a new notification.
     *
     * @param array $data
     * @return void
     */
    public function createNotification(array $data): void;

    /**
     * Mark a notification as read.
     *
     * @param int $id
     * @return void
     */
    public function markAsRead(int $id): void;

    /**
     * Notify super admins about critical stock levels.
     *
     * @param array $super_admins
     * @param array $critical_products
     * @return void
     */
    public function notifyAdminOfCriticalStock(array $super_admins, array $critical_products): void;
}
