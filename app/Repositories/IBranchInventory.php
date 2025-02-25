<?php
namespace App\Repositories;

interface IBranchInventory
{
    public function getAllInventoriesByBranchID();
    public function getAllInventoryProducts($branch_id);
    public function getSpecificInventoryProduct($branch_id,$product_id);
    public function storeNewInventoryProducts(array $data);
    public function updateSpecificInventoryProduct(array $data , $branch_id,$product_id);
    public function deleteAllInventoryProducts($branch_id);
    public function deleteSpecificProductsFromAllInventories(array|string $productIds);
    public function deleteSpecificInventoryProduct($branch_id,$product_id);
}
