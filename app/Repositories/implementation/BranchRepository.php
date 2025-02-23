<?php
namespace App\Repositories\implementation;

use App\Models\Branch;
use App\Repositories\IBranch;

class BranchRepository implements IBranch
{
    public function getAll()
    {
        return Branch::get();
    }

    public function findById($id)
    {
        if ($this->isExists($id)){
            return Branch::where('id',$id)->first();
        }else{
            return false;
        }
    }

    public function create(array $data)
    {
        return Branch::insertGetId($data);
    }

    public function update(array $data, $id)
    {
        if ($this->isExists($id)){
            return Branch::where('id',$id)->update($data);
        }else{
            return false;
        }
    }

    public function delete($id)
    {
        if ($this->isExists($id)){
            return Branch::where('id',$id)->delete();
        }else{
            return false;
        }
    }

    public function isExists($id)
    {
        return Branch::where('id',$id)->exists();
    }
}
