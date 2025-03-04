<?php
namespace App\Services\implementation;

use App\Repositories\IBranchInventory;
use App\Repositories\IProduct;
use App\Services\IBranchInventoryService;
use Illuminate\Support\Facades\DB;

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

    public function getCriticalProducts()
    {
        return $this->branchInventoryRepository->getCriticalProducts();
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
        return $this->branchInventoryRepository->deleteSpecificProductsFromAllInventories($product_id);
    }

    public function deleteSpecificInventoryProduct($branch_id, $product_id)
    {
        return $this->branchInventoryRepository->deleteSpecificInventoryProduct($branch_id,$product_id);
    }
    /***************************************
    * the array of data must have
    * - from_branch_id
    * - to_branch_id
    * - array of product_id
    * - array of quantity
    ****************************************/
    public function transferProductsBetweenInventories(array $data)
    {
        try {

            DB::beginTransaction();
            foreach ($data['product_id'] as $index => $product_id) {

                // add the products to the inventory which we will send the products
                $this->branchInventoryRepository
                    ->addProductsToInventory($data['to_branch_id'],$product_id,$data['quantity'][$index]);

                // reduce the products from the inventory which we will send the products
                $this->branchInventoryRepository
                    ->reduceProductsFromInventory($data['from_branch_id'],$product_id,$data['quantity'][$index]);
            }
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollBack();
            return false;
        }
    }


}
