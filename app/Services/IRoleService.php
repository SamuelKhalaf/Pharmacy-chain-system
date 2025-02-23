<?php
namespace App\Services;

interface IRoleService
{
    public function getAllRoles();
    public function getOneRole($id);
    public function createRole(array $data);
    public function updateRole(array $data,$id);
    public function deleteRole($id);
}
