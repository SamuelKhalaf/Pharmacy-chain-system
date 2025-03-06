<?php
namespace App\Repositories;

use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

interface IRole
{
    /**
     * Get all roles with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Find a role by ID.
     *
     * @param int $id
     * @return Role|null
     */
    public function findById(int $id): ?Role;

    /**
     * Create a new role and return its ID.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int;

    /**
     * Update a role by ID.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool;

    /**
     * Delete a role by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
