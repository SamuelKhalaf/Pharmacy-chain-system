<?php
namespace App\Services\implementation;

use App\Repositories\INotification;
use App\Services\INotificationService;

class NotificationService implements INotificationService
{

    protected INotification $notificationRepository;


    public function __construct(INotification $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }


    public function getAllNotifications()
    {
        $notifications = $this->notificationRepository->getAll();
        foreach ($notifications as $notification){
            $notification->data = json_decode($notification->data, true);
        }
        return $notifications;
    }
    public function getUnReadNotification()
    {
        $notifications = $this->notificationRepository->getUnReadNotification();
        $updatedData = [];
        foreach ($notifications as $notification)
        {
            $data = json_decode($notification->data, true);
            $updatedData[] = [
                'id' => $notification->id,
                'text' => $data['text'],
                'created_at' => $notification->created_at->diffForHumans(),
            ];
        }
        return $updatedData;
    }

    public function getOneNotification($id)
    {
        $notification = $this->notificationRepository->findById($id);
        $notification->data = json_decode($notification->data, true);
        return $notification;
    }

    public function createNotification(array $data)
    {
        $this->notificationRepository->create($data);
    }

    public function markAsRead($id)
    {
        $data = ['is_read' => true];
        $this->notificationRepository->update($data , $id);
    }
}
