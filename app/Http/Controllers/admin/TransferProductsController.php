<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetBranchInventoryRequest;
use App\Http\Requests\TransferredDataRequest;
use App\Services\IBranchInventoryService;
use App\Services\IBranchService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class TransferProductsController extends Controller
{
    protected IBranchService $branchService;
    protected IBranchInventoryService $branchInventoryService;

    /**
     * @param IBranchService $branchService
     * @param IBranchInventoryService $branchInventoryService
     */
    public function __construct(
        IBranchService $branchService,
        IBranchInventoryService $branchInventoryService
    ) {
        $this->branchService = $branchService;
        $this->branchInventoryService = $branchInventoryService;
    }

    /**
     * Show the form for transferring products between branches.
     *
     * @return Factory|Application|View
     */
    public function showTransferForm(): Factory|Application|View
    {
        $oldBranches = $this->branchService->getOldBranches();
        $allBranches = $this->branchService->getAllBranches();
        return view('admin.transfer.form', compact(['oldBranches', 'allBranches']));
    }

    /**
     * Get specific branch products for AJAX request.
     *
     * @param GetBranchInventoryRequest $request
     * @return JsonResponse
     */
    public function getSpecificBranchProducts(GetBranchInventoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $inventory = $this->branchInventoryService->getAllInventoryProducts($data['branch_id']);
        return response()->json(['data' => $inventory]);
    }

    /**
     * Store transferred products after admin approval or direct transfer by super admin.
     *
     * @param TransferredDataRequest $request
     * @return RedirectResponse
     */
    public function storeTransferredProducts(TransferredDataRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $transferred = $this->branchInventoryService->transferProductsBetweenInventories($data);

        if (!$transferred) {
            return redirect()->route('transfer.show-transfer-form')->with(['error' => 'An error occurred while transferring the products.']);
        }

        return redirect()->route('transfer.show-transfer-form')->with(['success' => 'Products transferred successfully.']);
    }
}
