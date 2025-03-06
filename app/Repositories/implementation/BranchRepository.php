<?php
namespace App\Repositories\implementation;

use App\Models\Branch;
use App\Repositories\IBranch;
use App\Repositories\IBranchInventory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BranchRepository implements IBranch
{
    protected IBranchInventory $branchInventoryRepository;

    public function __construct(IBranchInventory $branchInventoryRepository)
    {
        $this->branchInventoryRepository = $branchInventoryRepository;
    }

    /**
     * Get all branches with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll():LengthAwarePaginator
    {
        return Branch::paginate(PAGINATE_COUNT);
    }

    /**
     * Get all branches that don't have inventory.
     *
     * @return Collection
     */
    public function getNewBranches(): Collection
    {
        $allInventories = $this->branchInventoryRepository->getAllInventoriesByBranchID();
        return Branch::whereNotIn('id', $allInventories)->get();
    }

    /**
     * Get all branches that have inventory.
     *
     * @return Collection
     */
    public function getOldBranches(): Collection
    {
        $allInventories = $this->branchInventoryRepository->getAllInventoriesByBranchID();
        return Branch::whereIn('id', $allInventories)->get();
    }

    /**
     * Get all branches that have inventory except the authenticated user's branch.
     *
     * @return Collection
     */
    public function getOtherOldBranches(): Collection
    {
        $userBranchId = auth()->user()->branch_id;
        $allInventories = $this->branchInventoryRepository->getAllInventoriesByBranchID();

        return Branch::whereIn('id', $allInventories)
            ->where('id', '!=', $userBranchId)
            ->get();
    }

    /**
     * Get all branches that are not in the given product IDs list.
     *
     * @param array|int $productIds
     * @return Collection
     */
    public function getWhereNotIn(array|int $productIds): Collection
    {
        return Branch::whereNotIn('id', (array) $productIds)->get();
    }

    /**
     * Find a branch by ID.
     *
     * @param int $id
     * @return Branch|null
     */
    public function findById(int $id): ?Branch
    {
        if ($this->isExists($id)){
            return Branch::where('id',$id)->first();
        }
        return null;
    }

    /**
     * Create a new branch and return its ID.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int
    {
        return Branch::create($data)->id;
    }

    /**
     * Update a branch.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool
    {
        if ($this->isExists($id)){
            return Branch::where('id',$id)->update($data);
        }
        return false;
    }

    /**
     * Delete a branch by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        if ($this->isExists($id)){
            return Branch::where('id',$id)->delete();
        }
        return false;
    }

    /**
     * Check if a branch exists.
     *
     * @param int $id
     * @return bool
     */
    public function isExists(int $id): bool
    {
        return Branch::where('id', $id)->exists();
    }
}
