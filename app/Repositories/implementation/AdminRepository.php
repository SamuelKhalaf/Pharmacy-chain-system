<?php
namespace App\Repositories\implementation;

use App\Models\Admin;
use App\Repositories\IAdmin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminRepository implements IAdmin
{
    /**
     * Retrieve all admin records with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return Admin::paginate(PAGINATE_COUNT);
    }

    /**
     * Retrieve records based on a specific condition.
     *
     * @param string $column The column name to filter by.
     * @param string $operator The comparison operator (=, <, >, etc.).
     * @param mixed $value The value to compare.
     * @return Collection
     */
    public function getBy(string $column, string $operator, mixed $value): Collection
    {
        return Admin::query()
            ->where($column, $operator, $value)
            ->get();
    }

    /**
     * Find an admin by ID.
     *
     * @param int $id The ID of the admin.
     * @return Admin|null The admin model or null if not found.
     */
    public function findById(int $id): ?Admin
    {
        if ($this->isExists($id)) {
            return Admin::where('id', $id)->first();
        }
        return null;
    }

    /**
     * Create a new admin record.
     *
     * @param array $data The admin data.
     * @return int The ID of the created admin.
     */
    public function create(array $data): int
    {
        return Admin::create($data)->id;
    }

    /**
     * Update an existing admin record.
     *
     * @param array $data The updated data.
     * @param int $id The ID of the admin to update.
     * @return bool True if updated successfully, false otherwise.
     */
    public function update(array $data, int $id): bool
    {
        if ($this->isExists($id)) {
            return Admin::where('id', $id)->update($data) > 0;
        }
        return false;
    }

    /**
     * Delete an admin by ID.
     *
     * @param int $id The ID of the admin to delete.
     * @return bool True if deleted successfully, false otherwise.
     */
    public function delete(int $id): bool
    {
        if ($this->isExists($id)) {
            return Admin::where('id', $id)->delete() > 0;
        }
        return false;
    }

    /**
     * Check if an admin exists by ID.
     *
     * @param int $id The ID to check.
     * @return bool True if exists, false otherwise.
     */
    public function isExists(int $id): bool
    {
        return Admin::where('id', $id)->exists();
    }
}
