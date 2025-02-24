<?php
namespace App\Repositories;

interface IBranchInventory
{
    public function getAllInventoryProducts($branch_id);
    public function getSpecificInventoryProduct($branch_id,$product_id);
    public function storeInventoryProducts(array $data);
    public function updateInventoryProducts(array $data , $branch_id);
    public function deleteAllInventoryProducts($branch_id);
    public function deleteSpecificInventoryProduct($branch_id,$product_id);
}
