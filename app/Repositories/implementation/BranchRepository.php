<?php
namespace App\Repositories\implementation;

use App\Models\Branch;
use App\Models\BranchInventory;
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
        return Branch::get();
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

    public function getNewBranches()
    {
        $allInventories = $this->branchInventoryRepository->getAllInventoriesByBranchID();
        return Branch::whereNotIn('id',$allInventories)->get();
    }
}
