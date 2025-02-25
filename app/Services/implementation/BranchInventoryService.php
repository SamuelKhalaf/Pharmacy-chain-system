<?php
namespace App\Services\implementation;

use App\Repositories\IBranchInventory;
use App\Repositories\IProduct;
use App\Services\IBranchInventoryService;

class BranchInventoryService implements IBranchInventoryService
{
    protected IBranchInventory $branchInventoryRepository;
    protected IProduct $productRepository;

    public function __construct(IBranchInventory $branchInventoryRepository , IProduct $productRepository)
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

    public function storeNewInventoryProducts(array $data)
    {
        return $this->branchInventoryRepository->storeNewInventoryProducts($data);
    }

    public function updateSpecificInventoryProduct(array $data, $branch_id, $product_id)
    {
        return $this->branchInventoryRepository->updateSpecificInventoryProduct($data ,$branch_id ,$product_id);
    }

    public function deleteAllInventoryProducts($branch_id)
    {
        return $this->branchInventoryRepository->deleteAllInventoryProducts($branch_id);
    }
    public function deleteSpecificProductFromAllInventories($product_id)
    {
        return $this->branchInventoryRepository->deleteSpecificProductFromAllInventories($product_id);
    }

    public function deleteSpecificInventoryProduct($branch_id, $product_id)
    {
        return $this->branchInventoryRepository->deleteSpecificInventoryProduct($branch_id,$product_id);
    }

}
