<?php
namespace App\Adapters;

interface INotification
{
    /**
     * Send a notification.
     *
     * @param array $data Notification data
     * @return void
     */
    public function send(array $data): void;
}
