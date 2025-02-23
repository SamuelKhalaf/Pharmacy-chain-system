<?php
namespace App\Services;

interface IUserService
{
    public function getAllUsers();
    public function getOneUser($id);
    public function createUser(array $data);
    public function updateUser(array $data,$id);
    public function deleteUser($id);
}
