<?php

namespace App\Services\implementation;

use App\Services\IAuthAdminService;
use Illuminate\Support\Facades\Auth;

/**
 * Service for handling admin authentication.
 */
class AuthAdminAdminService implements IAuthAdminService
{
    /**
     * Attempt to log in an admin using the given credentials.
     *
     * @param array $credentials The login credentials (email & password).
     * @return bool True if login is successful, false otherwise.
     */
    public function login(array $credentials): bool
    {
        return Auth::guard('admin')->attempt($credentials);
    }

    /**
     * Log out the authenticated admin.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::guard('admin')->logout();
    }
}
