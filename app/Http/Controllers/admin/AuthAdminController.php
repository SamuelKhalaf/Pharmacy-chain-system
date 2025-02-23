<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Services\IAuthAdminService;

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
        return redirect()->route('home')->with(['success'=>'Login Successful']);
    }

    public function logout()
    {
        $this->authAdminService->logout();

        return redirect()->route('admin.login');
    }
}
