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
    public function send($data)
    {
        $this->notificationService->createNotification($data);
    }
}
