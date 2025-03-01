<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetBranchInventoryRequest;
use App\Http\Requests\TransferredDataRequest;
use App\Models\BranchInventory;
use App\Repositories\IBranch;
use App\Services\IBranchInventoryService;

class TransferProductsController extends Controller
{
    protected IBranch $branchRepository;
    protected IBranchInventoryService $branchInventoryService;

    public function __construct(IBranch $branchRepository , IBranchInventoryService $branchInventoryService)
    {
        $this->branchRepository = $branchRepository;
        $this->branchInventoryService = $branchInventoryService;
    }
    public function showTransferForm()
    {
        $oldBranches = $this->branchRepository->getOldBranches();
        $allBranches = $this->branchRepository->getAll();
        return view('admin.transfer.form',compact(['oldBranches','allBranches']));
    }

    // get data for ajax
    public function getSpecificBranchProducts(GetBranchInventoryRequest $request)
    {
        $data = $request->validated();
        $inventory = $this->branchInventoryService->getAllInventoryProducts($data['branch_id']);
        return response()->json(['data' => $inventory]);
    }

    // this method should be called after the admin accepts the transfer request
    // if the super admin who do the transfer , this method should be called directly
    public function storeTransferredProducts(TransferredDataRequest $request)
    {
        $data = $request->validated();
        $transferred = $this->branchInventoryService->transferProductsBetweenInventories($data);
        if (!$transferred){
            return redirect()->route('transfer.show-transfer-form')->with(['error' => 'An error occurred while transfer the products']);
        }
        return redirect()->route('transfer.show-transfer-form')->with(['success' => 'Products Transferred successfully']);
    }
}
