<?php
namespace App\Repositories\implementation;

use App\Models\Category;
use App\Repositories\ICategory;

class CategoryRepository implements ICategory
{
    public function getAll()
    {
        return Category::get();
    }
    public function findById($id)
    {
        if ($this->isExists($id)){
            return Category::where('id',$id)->first();
        }else{
            return false;
        }
    }

    public function create(array $data)
    {
        return Category::create($data)->id;
    }

    public function update(array $data, $id)
    {
        if ($this->isExists($id)){
            return Category::where('id',$id)->update($data);
        }else{
            return false;
        }
    }

    public function delete($id)
    {
        if ($this->isExists($id)){
            return Category::where('id',$id)->delete();
        }else{
            return false;
        }
    }

    public function isExists($id)
    {
        return Category::where('id',$id)->exists();
    }
}
