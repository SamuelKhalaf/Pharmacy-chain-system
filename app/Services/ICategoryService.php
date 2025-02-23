<?php
namespace App\Services;

interface ICategoryService
{
    public function getAllCategories();
    public function getOneCategory($id);
    public function createCategory(array $data);
    public function updateCategory(array $data,$id);
    public function deleteCategory($id);
}
