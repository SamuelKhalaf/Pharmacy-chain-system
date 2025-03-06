<?php

namespace App\Services;

/**
 * Interface for handling admin authentication.
 */
interface IAuthAdminService
{
    /**
     * Attempt to log in an admin with the provided credentials.
     *
     * @param array $credentials The login credentials (e.g., email and password).
     * @return bool True if login is successful, false otherwise.
     */
    public function login(array $credentials): bool;

    /**
     * Log out the currently authenticated admin.
     *
     * @return void
     */
    public function logout(): void;
}
