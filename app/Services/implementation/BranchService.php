<?php

namespace App\Services\implementation;

use App\Repositories\IBranch;
use App\Repositories\IBranchInventory;
use App\Services\IBranchService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BranchService implements IBranchService
{
    protected IBranch $branchRepository;
    protected IBranchInventory $branchInventoryRepository;

    /**
     * BranchService constructor.
     *
     * @param IBranch $branchRepository
     * @param IBranchInventory $branchInventoryRepository
     */
    public function __construct(IBranch $branchRepository, IBranchInventory $branchInventoryRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->branchInventoryRepository = $branchInventoryRepository;
    }

    /**
     * Get all branches.
     *
     * @return LengthAwarePaginator
     */
    public function getAllBranches(): LengthAwarePaginator
    {
        return $this->branchRepository->getAll();
    }

    /**
     * Get all branches without the authenticated admin branch.
     *
     * @param $branch_id
     * @return Collection
     */
    public function getWhereNotIn($branch_id): Collection
    {
        return $this->branchRepository->getWhereNotIn($branch_id);
    }

    /**
     * Get other old branches without the authenticated admin branch.
     *
     * @return Collection
     */
    public function getOtherOldBranches(): Collection
    {
        return $this->branchRepository->getOtherOldBranches();
    }

    /**
     * Get new branches.
     *
     * @return Collection
     */
    public function getNewBranches(): Collection
    {
        return $this->branchRepository->getNewBranches();
    }

    /**
     * Get old branches.
     *
     * @return Collection
     */
    public function getOldBranches(): Collection
    {
        return $this->branchRepository->getOldBranches();
    }

    /**
     * Get a single branch by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getOneBranch(int $id): mixed
    {
        return $this->branchRepository->findById($id);
    }

    /**
     * Create a new branch.
     *
     * @param array $data
     * @return int
     */
    public function createBranch(array $data): int
    {
        return $this->branchRepository->create($data);
    }

    /**
     * Update an existing branch.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateBranch(array $data, int $id): bool
    {
        return $this->branchRepository->update($data, $id);
    }

    /**
     * Delete a branch and all its inventory products.
     *
     * @param int $id
     * @return bool
     */
    public function deleteBranch(int $id): bool
    {
        try {
            DB::beginTransaction();
            $this->branchInventoryRepository->deleteAllInventoryProducts($id);
            $this->branchRepository->delete($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
