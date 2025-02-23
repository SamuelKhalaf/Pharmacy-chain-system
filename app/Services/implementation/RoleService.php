<?php
namespace App\Services\implementation;

use App\Repositories\implementation\RoleRepository;
use App\Repositories\IRole;
use App\Services\IRoleService;

class RoleService implements IRoleService
{
    protected IRole $roleRepository;

    public function __construct(IRole $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles()
    {
        return $this->roleRepository->getAll();
    }

    public function getOneRole($id)
    {
        return $this->roleRepository->findById($id);
    }

    public function createRole(array $data)
    {
        return $this->roleRepository->create($data);
    }

    public function updateRole(array $data,$id)
    {
        return $this->roleRepository->update($data, $id);
    }

    public function deleteRole($id)
    {
        return $this->roleRepository->delete($id);
    }
}
