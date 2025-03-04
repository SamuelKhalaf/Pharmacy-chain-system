<?php
namespace App\Services;

interface INotificationService
{
    public function getAllNotifications();
    public function getUnReadNotification();
    public function getOneNotification($id);
    public function createNotification(array $data);
    public function markAsRead($id);
}
