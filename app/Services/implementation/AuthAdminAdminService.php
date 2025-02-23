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
        if (!Auth::guard('admin')->attempt($credentials)){
            return false;
        }
        return true;
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
    }

}
