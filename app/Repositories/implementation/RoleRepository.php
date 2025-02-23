<?php
namespace App\Repositories\implementation;

use App\Models\Role;
use App\Repositories\IRole;

class RoleRepository implements IRole
{
    public function getAll()
    {
        return Role::get();
    }

    public function findById($id)
    {
        if ($this->isExists($id)){
            return Role::where('id',$id)->first();
        }else{
            return false;
        }
    }

    public function create(array $data)
    {
        return Role::insertGetId($data);
    }

    public function update(array $data, $id)
    {
        if ($this->isExists($id)){
            return Role::where('id',$id)->update($data);
        }else{
            return false;
        }
    }

    public function delete($id)
    {
        if ($this->isExists($id)){
            return Role::where('id',$id)->delete();
        }else{
            return false;
        }
    }

    public function isExists($id)
    {
        return Role::where('id',$id)->exists();
    }
}
