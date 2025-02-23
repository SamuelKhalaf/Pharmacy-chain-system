<?php
namespace App\Repositories\implementation;

use App\Models\Admin;
use App\Repositories\IAdmin;

class AdminRepository implements IAdmin
{
    public function getAll()
    {
        return Admin::get();
    }

    public function getBy($column,$operator,$value)
    {
        return Admin::query()
            ->where($column,$operator,$value)
            ->get();
    }

    public function findById($id)
    {
        if ($this->isExists($id)){
            return Admin::where('id',$id)->first();
        }else{
            return false;
        }
    }

    public function create(array $data)
    {
        return Admin::create($data)->id;
    }

    public function update(array $data, $id)
    {
        if ($this->isExists($id)){
            return Admin::where('id',$id)->update($data);
        }else{
            return false;
        }
    }

    public function delete($id)
    {
        if ($this->isExists($id)){
            return Admin::where('id',$id)->delete();
        }else{
            return false;
        }
    }

    public function isExists($id)
    {
        return Admin::where('id',$id)->exists();
    }
}
