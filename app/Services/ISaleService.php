<?php
namespace App\Services;

interface ISaleService
{
    public function getSales();
    public function getSaleByBranchId(int $branch_id);
    public function getSpecificSaleItems($sale_id);
    public function createSaleWithItems(array $data);
    public function updateSaleWithItems(array $data, int $id);
    public function removeSale(int $id);
    public function isSaleExists($id);
}

