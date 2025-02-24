<?php
namespace App\Services;

interface IBranchInventoryService
{
    public function getAllInventoryProducts($branch_id);
    public function getOneInventoryProduct($branch_id,$product_id);
    public function storeInventoryProducts(array $data);
    public function updateInventoryProducts(array $data,$branch_id);
    public function deleteInventoryProduct($branch_id);
}
