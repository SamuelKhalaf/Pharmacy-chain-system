<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface for admin service operations.
 */
interface IAdminService
{
    /**
     * Retrieve all admins.
     *
     * @return LengthAwarePaginator Paginated collection of admins.
     */
    public function getAllAdmins(): LengthAwarePaginator;

    /**
     * Retrieve admins by role ID.
     *
     * @param int $roleId The role ID.
     * @return Collection List of admins with the specified role.
     */
    public function getByRoleId(int $roleId): Collection;

    /**
     * Retrieve a single admin by ID.
     *
     * @param int $id The admin ID.
     * @return mixed The admin data or false if not found.
     */
    public function getOneAdmin(int $id): mixed;

    /**
     * Create a new admin.
     *
     * @param array $data The admin data.
     * @return int The ID of the newly created admin.
     */
    public function createAdmin(array $data): int;

    /**
     * Update an existing admin.
     *
     * @param array $data The updated admin data.
     * @param int $id The admin ID.
     * @return bool Whether the update was successful.
     */
    public function updateAdmin(array $data, int $id): bool;

    /**
     * Delete an admin.
     *
     * @param int $id The admin ID.
     * @return bool Whether the deletion was successful.
     */
    public function deleteAdmin(int $id): bool;
}
