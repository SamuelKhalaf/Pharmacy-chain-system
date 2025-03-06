<?php

namespace App\Services\implementation;

use App\Models\Role;
use App\Repositories\IRole;
use App\Services\IRoleService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class RoleService
 * Implements role management functionalities.
 */
class RoleService implements IRoleService
{
    /**
     * @var IRole
     */
    protected IRole $roleRepository;

    /**
     * RoleService constructor.
     *
     * @param IRole $roleRepository
     */
    public function __construct(IRole $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Retrieve all roles.
     *
     * @return LengthAwarePaginator
     */
    public function getAllRoles(): LengthAwarePaginator
    {
        return $this->roleRepository->getAll();
    }

    /**
     * Retrieve a specific role by its ID.
     *
     * @param int $id
     * @return Role|null
     */
    public function getOneRole(int $id): ?Role
    {
        return $this->roleRepository->findById($id);
    }

    /**
     * Create a new role.
     *
     * @param array $data
     * @return int
     */
    public function createRole(array $data): int
    {
        return $this->roleRepository->create($data);
    }

    /**
     * Update an existing role by its ID.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateRole(array $data, int $id): bool
    {
        return $this->roleRepository->update($data, $id);
    }

    /**
     * Delete a role by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteRole(int $id): bool
    {
        return $this->roleRepository->delete($id);
    }
}
