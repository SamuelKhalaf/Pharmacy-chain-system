<?php
namespace App\Repositories;

interface ISale
{
    public function getAll();
    public function findByBranchId($branch_id);
    public function getSpecificSaleItems($sale_id);
    public function create(array $data);
    public function update(array $data , $id);
    public function delete($id);
    public function isExists($id);
}
