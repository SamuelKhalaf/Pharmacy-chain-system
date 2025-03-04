<?php
namespace App\Repositories;

interface IProduct
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update(array $data , $id);
    public function delete($id);
    public function deleteProductsByCategoryId($category_id);
    public function getTopSellingProducts($year, $limit);
}
