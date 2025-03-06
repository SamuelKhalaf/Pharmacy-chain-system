<?php
namespace App\Repositories\implementation;

use App\Models\Role;
use App\Repositories\IRole;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleRepository implements IRole
{
    /**
     * Get all roles with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return Role::paginate(PAGINATE_COUNT);
    }

    /**
     * Find a role by ID.
     *
     * @param int $id
     * @return Role|null
     */
    public function findById(int $id): ?Role
    {
        return $this->isExists($id) ? Role::where('id', $id)->first() : null;
    }

    /**
     * Create a new role and return its ID.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int
    {
        return Role::insertGetId($data);
    }

    /**
     * Update a role by ID.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool
    {
        return $this->isExists($id) ? Role::where('id', $id)->update($data) : false;
    }

    /**
     * Delete a role by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->isExists($id) ? Role::where('id', $id)->delete() : false;
    }

    /**
     * Check if a role exists by ID.
     *
     * @param int $id
     * @return bool
     */
    public function isExists(int $id): bool
    {
        return Role::where('id', $id)->exists();
    }
}
