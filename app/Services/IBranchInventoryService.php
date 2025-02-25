<?php
namespace App\Services;

interface IBranchInventoryService
{
    public function getAllInventoryProducts($branch_id);
    public function getOneInventoryProduct($branch_id,$product_id);
    public function storeNewInventoryProducts(array $data);
    public function updateSpecificInventoryProduct(array $data,$branch_id,$product_id);
    public function deleteAllInventoryProducts($branch_id);
    public function deleteSpecificProductFromAllInventories($product_id);
    public function deleteSpecificInventoryProduct($branch_id,$product_id);
}
