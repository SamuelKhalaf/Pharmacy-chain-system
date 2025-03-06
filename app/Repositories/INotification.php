<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface INotification
{
    /**
     * Get all notifications.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get unread notifications.
     *
     * @return Collection
     */
    public function getUnReadNotification(): Collection;

    /**
     * Get notifications by a specific column condition.
     *
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return Collection
     */
    public function getBy(string $column, string $operator, mixed $value): Collection;

    /**
     * Find a notification by ID.
     *
     * @param int $id
     * @return object|null
     */
    public function findById(int $id): ?object;

    /**
     * Create a new notification.
     *
     * @param array $data
     * @return object
     */
    public function create(array $data): object;

    /**
     * Update a notification by ID.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool;
}
