<?php
namespace App\Repositories;

interface ISale
{
    public function getAll();
    public function findByBranchId($branch_id, $start_date = null, $end_date = null);
    public function getSpecificSaleItems($sale_id);
    public function create(array $data);
    public function update(array $data , $id);
    public function delete($id);
    public function getBranchSalesCountByMonth($month, $year);
    public function getSoldProductQuantity($branch_id, $product_id , $start_date = null, $end_date = null);
    public function isExists($id);
}
