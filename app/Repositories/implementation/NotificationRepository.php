<?php
namespace App\Repositories\implementation;

use App\Models\Notification;
use App\Repositories\INotification;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository implements INotification
{
    /**
     * Get all notifications for the authenticated admin.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Notification::query()
            ->where('admin_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get the last 4 unread notifications for the authenticated admin.
     *
     * @return Collection
     */
    public function getUnReadNotification(): Collection
    {
        return Notification::query()
            ->where('is_read', false)
            ->where('admin_id', auth()->id())
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();
    }

    /**
     * Get notifications by a specific column condition.
     *
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return Collection
     */
    public function getBy(string $column, string $operator, mixed $value): Collection
    {
        return Notification::query()
            ->where($column, $operator, $value)
            ->get();
    }

    /**
     * Find a notification by ID.
     *
     * @param int $id
     * @return Notification|null
     */
    public function findById(int $id): ?Notification
    {
        return Notification::find($id);
    }

    /**
     * Create a new notification.
     *
     * @param array $data
     * @return Notification
     */
    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    /**
     * Update a notification by ID.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool
    {
        return (bool) Notification::where('id', $id)->update($data);
    }

    /**
     * Check if a notification exists.
     *
     * @param int $id
     * @return bool
     */
    public function isExists(int $id): bool
    {
        return Notification::where('id', $id)->exists();
    }
}
