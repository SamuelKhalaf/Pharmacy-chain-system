<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface IRoleService
 * Defines the contract for role management operations.
 */
interface IRoleService
{
    /**
     * Retrieve all roles.
     *
     * @return LengthAwarePaginator
     */
    public function getAllRoles(): LengthAwarePaginator;

    /**
     * Retrieve a specific role by its ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getOneRole(int $id): mixed;

    /**
     * Create a new role.
     *
     * @param array $data
     * @return mixed
     */
    public function createRole(array $data): mixed;

    /**
     * Update an existing role by its ID.
     *
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function updateRole(array $data, int $id): mixed;

    /**
     * Delete a role by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteRole(int $id): bool;
}
