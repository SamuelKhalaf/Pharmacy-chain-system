<?php
namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Admin;

interface IAdmin
{
    /**
     * Retrieve all admin records with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Retrieve records based on a specific condition.
     *
     * @param string $column The column name to filter by.
     * @param string $operator The comparison operator (=, <, >, etc.).
     * @param mixed $value The value to compare.
     * @return Collection
     */
    public function getBy(string $column, string $operator, mixed $value): Collection;

    /**
     * Find an admin by ID.
     *
     * @param int $id The ID of the admin.
     * @return Admin|null The admin model or null if not found.
     */
    public function findById(int $id): ?Admin;

    /**
     * Create a new admin record.
     *
     * @param array $data The admin data.
     * @return int The ID of the created admin.
     */
    public function create(array $data): int;

    /**
     * Update an existing admin record.
     *
     * @param array $data The updated data.
     * @param int $id The ID of the admin to update.
     * @return bool True if updated successfully, false otherwise.
     */
    public function update(array $data, int $id): bool;

    /**
     * Delete an admin by ID.
     *
     * @param int $id The ID of the admin to delete.
     * @return bool True if deleted successfully, false otherwise.
     */
    public function delete(int $id): bool;
}
