<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use App\Services\IBranchService;
use App\Services\IRoleService;
use App\Services\IAdminService;
use Mockery\Exception;

class AdminController extends Controller
{
    protected IAdminService $adminService;
    protected IRoleService $roleService;
    protected IBranchService $branchService;

    public function __construct(IRoleService $roleService, IBranchService $branchService,
                                IAdminService $adminService)
    {
        $this->roleService = $roleService;
        $this->branchService = $branchService;
        $this->adminService = $adminService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = $this->adminService->getAllAdmins();
        return view('admin.admin.index',compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = $this->branchService->getAllBranches();
        $roles = $this->roleService->getAllRoles();
        return view('admin.admin.create' , compact('branches' , 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAdminRequest $request)
    {
        $data = $request->validated();

        $stored = $this->adminService->createAdmin($data);
        if ($stored){
            return redirect()->route('admin.index')->with(['success' => 'admin added successfully']);
        }else{
            return redirect()->route('admin.index')->with(['error' => 'an error occurred while add the admin data']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $admin = $this->adminService->getOneAdmin($id);
        return view('admin.admin.view' , compact(['admin']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $branches = $this->branchService->getAllBranches();
        $roles = $this->roleService->getAllRoles();
        $admin = $this->adminService->getOneAdmin($id);
        return view('admin.admin.edit' , compact(['branches','roles','admin']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $data = $request->validated();
        $updated = $this->adminService->updateAdmin($data,$admin->id);

        if ($updated){
            return redirect()->route('admin.index')->with(['success' => 'admin data updated successfully']);
        }else{

            return redirect()->route('admin.index')->with(['error' => 'an error occurred while update the admin data']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $deleted = $this->adminService->deleteAdmin($id);
        if ($deleted){
            return redirect()->route('admin.index')->with(['success' => 'admin data deleted successfully']);
        }else{
            return redirect()->route('admin.index')->with(['error' => 'an error occurred while delete the admin data']);
        }
    }

}
