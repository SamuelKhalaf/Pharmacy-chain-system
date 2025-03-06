<?php

namespace App\Http\Controllers\admin;

use App\Enums\AdminRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Services\IAuthAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthAdminController extends Controller
{
    protected IAuthAdminService $authAdminService;

    /**
     * @param IAuthAdminService $authAdminService
     */
    public function __construct(IAuthAdminService $authAdminService)
    {
        $this->authAdminService = $authAdminService;
    }

    /**
     * Display the admin login form.
     *
     * @return View
     */
    public function showAdminLoginForm(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an admin login attempt.
     *
     * @param AdminLoginRequest $request
     * @return RedirectResponse|View|Response
     */
    public function adminLogin(AdminLoginRequest $request): RedirectResponse|View|Response
    {
        $credentials = $request->validated();

        $loggedIn = $this->authAdminService->login($credentials);
        if (!$loggedIn) {
            return redirect()->route('admin.login')->withErrors(['email' => 'Invalid Credentials']);
        }

        $user = Auth::guard('admin')->user();
        if ($user->role_id === AdminRole::SuperAdmin->value) {
            return redirect()->route('home')->with(['success' => 'Login Successful']);
        } elseif ($user->role_id === AdminRole::BranchAdmin->value) {
            return redirect()->route('pharmacy.index')->with(['success' => 'Login Successful']);
        }

        return response()->view('errors.403', [], 403);
    }

    /**
     * Log out the admin user.
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->authAdminService->logout();
        return redirect()->route('admin.login');
    }
}
