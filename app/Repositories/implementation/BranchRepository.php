<?php
namespace App\Repositories\implementation;

use App\Models\Branch;
use App\Repositories\IBranch;
use App\Repositories\IBranchInventory;

class BranchRepository implements IBranch
{
    protected IBranchInventory $branchInventoryRepository;

    public function __construct(IBranchInventory $branchInventoryRepository)
    {
        $this->branchInventoryRepository = $branchInventoryRepository;
    }
    public function getAll()
    {
        return Branch::paginate(PAGINATE_COUNT);
    }

    // All Branches that don't have inventory
    public function getNewBranches()
    {
        $allInventories = $this->branchInventoryRepository->getAllInventoriesByBranchID();
        return Branch::whereNotIn('id',$allInventories)->get();
    }
    // All Branches that have inventory
    public function getOldBranches()
    {
        $allInventories = $this->branchInventoryRepository->getAllInventoriesByBranchID();
        return Branch::whereIn('id',$allInventories)->get();
    }
    // Get all branches that have inventory except the auth user branch
    public function getOtherOldBranches()
    {
        $userBranchId = auth()->user()->branch_id;
        $allInventories = $this->branchInventoryRepository->getAllInventoriesByBranchID();

        return Branch::query()
            ->whereIn('id', $allInventories)
            ->where('id', '!=', $userBranchId)
            ->get();
    }

    public function getWhereNotIn($productIds)
    {
        if (!is_array($productIds)) {
            $productIds = [$productIds];
        }

        return Branch::whereNotIn('id', $productIds)->get();
    }

    public function findById($id)
    {
        if ($this->isExists($id)){
            return Branch::where('id',$id)->first();
        }else{
            return false;
        }
    }

    public function create(array $data)
    {
        return Branch::create($data)->id;
    }

    public function update(array $data, $id)
    {
        if ($this->isExists($id)){
            return Branch::where('id',$id)->update($data);
        }else{
            return false;
        }
    }

    public function delete($id)
    {
        if ($this->isExists($id)){
            return Branch::where('id',$id)->delete();
        }else{
            return false;
        }
    }

    public function isExists($id)
    {
        return Branch::where('id',$id)->exists();
    }
}
