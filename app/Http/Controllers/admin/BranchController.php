<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Services\IBranchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BranchController extends Controller
{
    protected IBranchService $branchService;

    /**
     * @param IBranchService $branchService
     */
    public function __construct(IBranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $branches = $this->branchService->getAllBranches();
        return view('admin.branch.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.branch.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BranchRequest $request
     * @return RedirectResponse
     */
    public function store(BranchRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $created = $this->branchService->createBranch($data);
        if (!$created) {
            return redirect()->route('branch.create')->with(['error' => 'An error occurred while saving the branch data']);
        }
        return redirect()->route('branch.index')->with(['success' => 'Branch data saved successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return View
     */
    public function show(string $id): View
    {
        $branch = $this->branchService->getOneBranch($id);
        return view('admin.branch.view', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        $branch = $this->branchService->getOneBranch($id);
        return view('admin.branch.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BranchRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(BranchRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();

        $updated = $this->branchService->updateBranch($data, $id);
        if (!$updated) {
            return redirect()->route('branch.update')->with(['error' => 'An error occurred while updating the branch data']);
        }
        return redirect()->route('branch.index')->with(['success' => 'Branch data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $deleted = $this->branchService->deleteBranch($id);
        if ($deleted) {
            return redirect()->route('branch.index')->with(['success' => 'Branch data deleted successfully']);
        }
        return redirect()->route('branch.index')->with(['error' => 'An error occurred while deleting the branch data']);
    }
}
