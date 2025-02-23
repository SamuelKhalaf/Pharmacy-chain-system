<?php

namespace App\Http\Controllers\admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateModeratorRequest;
use App\Http\Requests\UpdateModeratorRequest;
use App\Models\Branch;
use App\Models\User;
use App\Repositories\IRole;
use App\Services\IBranchService;
use App\Services\IRoleService;
use App\Services\IUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModeratorController extends Controller
{
    protected IUserService $userService;
    protected IRoleService $roleService;
    protected IBranchService $branchService;

    public function __construct(IRoleService $roleService, IBranchService $branchService,
                                IUserService $userService)
    {
        $this->roleService = $roleService;
        $this->branchService = $branchService;
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $moderators = $this->userService->getAllModerators();
        return view('admin.moderator.index',compact('moderators'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = $this->branchService->getAllBranches();
        $roles = $this->roleService->getAllRoles();
        return view('admin.moderator.create' , compact('branches' , 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateModeratorRequest $request)
    {
        $data = $request->validated();

        $stored = $this->userService->createUser($data);
        if ($stored){
            return redirect()->route('moderator.index')->with(['success' => 'user added successfully']);
        }else{
            return redirect()->route('moderator.index')->with(['error' => 'an error occurred while add the user data']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $moderator = $this->userService->getOneUser($id);
        return view('admin.moderator.view' , compact(['moderator']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $branches = $this->branchService->getAllBranches();
        $roles = $this->roleService->getAllRoles();
        $moderator = $this->userService->getOneUser($id);
        return view('admin.moderator.edit' , compact(['branches','roles','moderator']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModeratorRequest $request,User $moderator)
    {
        $data = $request->validated();

        $updated = $this->userService->updateUser($data,$moderator->id);
        if ($updated){
            return redirect()->route('moderator.index')->with(['success' => 'user data updated successfully']);
        }else{
            return redirect()->route('moderator.index')->with(['error' => 'an error occurred while update the user data']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $deleted = $this->userService->deleteUser($id);
        if ($deleted){
            return redirect()->route('moderator.index')->with(['success' => 'user data deleted successfully']);
        }else{
            return redirect()->route('moderator.index')->with(['error' => 'an error occurred while delete the user data']);
        }
    }

}
