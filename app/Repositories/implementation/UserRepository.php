<?php
namespace App\Repositories\implementation;

use App\Models\User;
use App\Repositories\IUser;

class UserRepository implements IUser
{
    public function getAll()
    {
        return User::get();
    }

    public function getBy($column,$operator,$value)
    {
        return User::query()
            ->where($column,$operator,$value)->get();
    }

    public function findById($id)
    {
        if ($this->isExists($id)){
            return User::where('id',$id)->first();
        }else{
            return false;
        }
    }

    public function create(array $data)
    {
        return User::create($data)->id;
    }

    public function update(array $data, $id)
    {
        if ($this->isExists($id)){
            return User::where('id',$id)->update($data);
        }else{
            return false;
        }
    }

    public function delete($id)
    {
        if ($this->isExists($id)){
            return User::where('id',$id)->delete();
        }else{
            return false;
        }
    }

    public function isExists($id)
    {
        return User::where('id',$id)->exists();
    }
}
