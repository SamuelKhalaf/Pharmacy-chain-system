<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface IUser
{
    /**
     * Get all users with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return User|bool|null
     */
    public function findById(int $id): User|bool|null;

    /**
     * Create a new user and return the ID.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int;

    /**
     * Update an existing user by ID.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool;

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
