<?php

namespace App\Adapters\implementation;

use App\Adapters\INotification;
use App\Services\INotificationService;

class DatabaseNotificationAdapter implements INotification
{
    protected INotificationService $notificationService;

    public function __construct(INotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * Send a notification using the notification service.
     *
     * @param array $data
     * @return void
     */
    public function send(array $data): void
    {
        $this->notificationService->createNotification($data);
    }
}
