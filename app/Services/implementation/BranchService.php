<?php
namespace App\Services\implementation;

use App\Repositories\IBranch;
use App\Repositories\implementation\RoleRepository;
use App\Services\IBranchService;
use App\Services\IRoleService;

class BranchService implements IBranchService
{
    protected IBranch $branchRepository;

    public function __construct(IBranch $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function getAllBranches()
    {
        return $this->branchRepository->getAll();
    }

    public function getOneBranch($id)
    {
        return $this->branchRepository->findById($id);
    }

    public function createBranch(array $data)
    {
        return $this->branchRepository->create($data);
    }

    public function updateBranch(array $data,$id)
    {
        return $this->branchRepository->update($data, $id);
    }

    public function deleteBranch($id)
    {
        return $this->branchRepository->delete($id);
    }
}
