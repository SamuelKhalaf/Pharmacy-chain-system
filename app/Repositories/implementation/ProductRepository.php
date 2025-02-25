<?php
namespace App\Repositories\implementation;

use App\Models\Product;
use App\Repositories\IProduct;

class ProductRepository implements IProduct
{
    public function getAll()
    {
        return Product::get();
    }
    public function findById($id)
    {
        if ($this->isExists($id)){
            return Product::where('id',$id)->first();
        }else{
            return false;
        }
    }

    public function create(array $data)
    {
        return Product::create($data)->id;
    }

    public function update(array $data, $id)
    {
        if ($this->isExists($id)){
            return Product::where('id',$id)->update($data);
        }else{
            return false;
        }
    }

    public function delete($id)
    {
        if ($this->isExists($id)){
            return Product::where('id',$id)->delete();
        }else{
            return false;
        }
    }
    public function deleteProductsByCategoryId($category_id)
    {
        $productIds = Product::where('category_id', $category_id)->pluck('id')->toArray();

        Product::where('category_id', $category_id)->delete();

        return $productIds;
    }

    public function isExists($id)
    {
        return Product::where('id',$id)->exists();
    }
}
