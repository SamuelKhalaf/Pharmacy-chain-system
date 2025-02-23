<?php
namespace App\Services;

interface IAuthAdminService
{
    public function login($credentials);
    public function logout();

}
