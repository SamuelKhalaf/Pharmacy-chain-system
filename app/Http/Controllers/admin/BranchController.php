<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use App\Services\IBranchInventoryService;
use App\Services\IBranchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    protected IBranchService $branchService;

    public function __construct(IBranchService $branchService)
    {
        $this->branchService = $branchService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = $this->branchService->getAllBranches();
        return view('admin.branch.index',compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.branch.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchRequest $request)
    {
        $data = $request->validated();

        $created = $this->branchService->createBranch($data);
        if (!$created){
            return redirect()->route('branch.create')->with(['error' => 'an error occurred while save the branch data']);
        }
        return redirect()->route('branch.index')->with(['success' => 'branch data saved successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $branch = $this->branchService->getOneBranch($id);
        return view('admin.branch.view',compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $branch = $this->branchService->getOneBranch($id);
        return view('admin.branch.edit',compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BranchRequest $request, string $id)
    {
        $data = $request->validated();

        $updated = $this->branchService->updateBranch($data,$id);
        if (!$updated){
            return redirect()->route('branch.update')->with(['error' => 'an error occurred while update the branch data']);
        }
        return redirect()->route('branch.index')->with(['success' => 'branch data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->branchService->deleteBranch($id);
        if ($deleted){
            return redirect()->route('branch.index')->with(['success' => 'branch data deleted successfully']);
        }else{
            return redirect()->route('branch.index')->with(['error' => 'an error occurred while delete the branch data']);
        }
    }
}
