<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use App\Services\IBranchService;
use App\Services\IRoleService;
use App\Services\IAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    protected IAdminService $adminService;
    protected IRoleService $roleService;
    protected IBranchService $branchService;

    /**
     * @param IRoleService $roleService
     * @param IBranchService $branchService
     * @param IAdminService $adminService
     */
    public function __construct(IRoleService $roleService, IBranchService $branchService, IAdminService $adminService)
    {
        $this->roleService = $roleService;
        $this->branchService = $branchService;
        $this->adminService = $adminService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $admins = $this->adminService->getAllAdmins();
        return view('admin.admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $branches = $this->branchService->getAllBranches();
        $roles = $this->roleService->getAllRoles();
        return view('admin.admin.create', compact('branches', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAdminRequest $request
     * @return RedirectResponse
     */
    public function store(CreateAdminRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $stored = $this->adminService->createAdmin($data);

        if ($stored) {
            return redirect()->route('admin.index')->with(['success' => 'admin added successfully']);
        }

        return redirect()->route('admin.index')->with(['error' => 'an error occurred while adding the admin data']);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return View
     */
    public function show(string $id): View
    {
        $admin = $this->adminService->getOneAdmin($id);
        return view('admin.admin.view', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        $branches = $this->branchService->getAllBranches();
        $roles = $this->roleService->getAllRoles();
        $admin = $this->adminService->getOneAdmin($id);
        return view('admin.admin.edit', compact('branches', 'roles', 'admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdminRequest $request
     * @param Admin $admin
     * @return RedirectResponse
     */
    public function update(UpdateAdminRequest $request, Admin $admin): RedirectResponse
    {
        $data = $request->validated();
        $updated = $this->adminService->updateAdmin($data, $admin->id);

        if ($updated) {
            return redirect()->route('admin.index')->with(['success' => 'admin data updated successfully']);
        }

        return redirect()->route('admin.index')->with(['error' => 'an error occurred while updating the admin data']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $deleted = $this->adminService->deleteAdmin($id);

        if ($deleted) {
            return redirect()->route('admin.index')->with(['success' => 'admin data deleted successfully']);
        }

        return redirect()->route('admin.index')->with(['error' => 'an error occurred while deleting the admin data']);
    }
}
