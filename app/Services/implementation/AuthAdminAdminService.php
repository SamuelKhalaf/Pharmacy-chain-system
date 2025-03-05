<?php
namespace App\Services\implementation;

use App\Services\IAuthAdminService;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class AuthAdminAdminService implements IAuthAdminService
{
    public function login($credentials)
    {
       return Auth::guard('admin')->attempt($credentials);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
    }

}
