<?php

namespace App\Adapters\implementation;

use App\Adapters\INotification;

class DatabaseNotificationAdapter implements INotification
{
    protected \App\Repositories\INotification $notificationRepository;

    public function __construct(\App\Repositories\INotification  $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }
    /**
     * Send a notification using the notification service.
     *
     * @param array $data
     * @return void
     */
    public function send(array $data): void
    {
        $this->notificationRepository->create($data);
    }
}
