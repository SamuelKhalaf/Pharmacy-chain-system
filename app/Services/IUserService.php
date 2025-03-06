<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface IUserService
 *
 * Defines methods for user management.
 */
interface IUserService
{
    /**
     * Retrieve all users.
     *
     * @return LengthAwarePaginator
     */
    public function getAllUsers(): LengthAwarePaginator;

    /**
     * Retrieve a specific user by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getOneUser(int $id): mixed;

    /**
     * Create a new user.
     *
     * @param array $data
     * @return mixed
     */
    public function createUser(array $data): mixed;

    /**
     * Update an existing user.
     *
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function updateUser(array $data, int $id): mixed;

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool;
}
