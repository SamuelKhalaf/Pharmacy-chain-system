<?php
namespace App\Services;

interface IAdminService
{
    public function getAllAdmins();
    public function getOneAdmin($id);
    public function createAdmin(array $data);
    public function updateAdmin(array $data,$id);
    public function deleteAdmin($id);
}
