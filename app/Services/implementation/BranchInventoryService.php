<?php
namespace App\Services\implementation;

use App\Repositories\IBranchInventory;
use App\Services\IBranchInventoryService;

class BranchInventoryService implements IBranchInventoryService
{
    protected IBranchInventory $branchInventoryRepository;

    public function __construct(IBranchInventory $branchInventoryRepository)
    {
        $this->branchInventoryRepository = $branchInventoryRepository;
    }

    public function getAllInventoryProducts($branch_id)
    {
        return $this->branchInventoryRepository->getAllInventoryProducts($branch_id);
    }

    public function getOneInventoryProduct($branch_id, $product_id)
    {
        return $this->branchInventoryRepository->getSpecificInventoryProduct($branch_id,$product_id);
    }

    public function storeInventoryProducts(array $data)
    {
        return $this->branchInventoryRepository->storeInventoryProducts($data);
    }

    public function updateInventoryProducts(array $data, $branch_id)
    {
        // TODO: Implement updateInventoryProducts() method.
    }

    public function deleteInventoryProduct($branch_id)
    {
        // TODO: Implement deleteInventoryProduct() method.
    }


}
