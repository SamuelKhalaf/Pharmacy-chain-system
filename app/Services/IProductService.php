<?php
namespace App\Services;

interface IProductService
{
    public function getAllProducts();
    public function getOneProduct($id);
    public function createProduct(array $data);
    public function updateProduct(array $data,$id);
    public function deleteProduct($id);
}
