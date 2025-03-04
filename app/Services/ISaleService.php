<?php
namespace App\Services;

interface ISaleService
{
    public function getSales();
    public function getSaleByBranchId(int $branch_id, $start_date = null, $end_date = null);
    public function getSpecificSaleItems($sale_id);
    public function createSaleWithItems(array $data);
    public function updateSaleWithItems(array $data, int $id);
    public function removeSale(int $id);
    public function getBranchSalesByMonth($month, $year);
    public function getSoldProductQuantity($branchId, $productId , $startDate = null, $endDate = null);
}

