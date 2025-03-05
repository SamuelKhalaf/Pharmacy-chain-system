<?php

namespace App\Http\Controllers\admin;

use App\Enums\AdminRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Services\IAuthAdminService;
use Illuminate\Support\Facades\Auth;

class AuthAdminController extends Controller
{
    protected IAuthAdminService $authAdminService;
    public function __construct(IAuthAdminService $authAdminService)
    {
        $this->authAdminService = $authAdminService;
    }
    public function showAdminLoginForm()
    {
        return view('admin.auth.login');
    }

    public function adminLogin(AdminLoginRequest $request)
    {
        $credentials = $request->validated();

        $loggedIn = $this->authAdminService->login($credentials);
        if (!$loggedIn){
            return redirect()->route('admin.login')->withErrors(['email'=>'Invalid Credentials']);
        }
        $user = Auth::guard('admin')->user();
        if ($user->role_id === AdminRole::SuperAdmin->value) {
            return redirect()->route('home')->with(['success'=>'Login Successful']);
        } elseif ($user->role_id === AdminRole::BranchAdmin->value) {
            return redirect()->route('pharmacy.index')->with(['success'=>'Login Successful']);
        }
        return response()->view('errors.403', [], 403);
    }

    public function logout()
    {
        $this->authAdminService->logout();

        return redirect()->route('admin.login');
    }
}
