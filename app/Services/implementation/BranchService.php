<?php
namespace App\Services\implementation;

use App\Repositories\IBranch;
use App\Repositories\IBranchInventory;
use App\Repositories\implementation\RoleRepository;
use App\Services\IBranchService;
use App\Services\IRoleService;
use Illuminate\Support\Facades\DB;

class BranchService implements IBranchService
{
    protected IBranch $branchRepository;
    protected IBranchInventory $branchInventoryRepository;

    public function __construct(IBranch $branchRepository , IBranchInventory $branchInventoryRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->branchInventoryRepository = $branchInventoryRepository;
    }

    public function getAllBranches()
    {
        return $this->branchRepository->getAll();
    }

    public function getNewBranches()
    {
        return $this->branchRepository->getNewBranches();
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
        try {
            DB::beginTransaction();
            $this->branchInventoryRepository->deleteAllInventoryProducts($id);
            $this->branchRepository->delete($id);
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollBack();
            return false;
        }
    }
}
