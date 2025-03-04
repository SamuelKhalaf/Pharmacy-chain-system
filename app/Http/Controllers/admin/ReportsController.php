<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\IBranchInventoryService;
use App\Services\IBranchService;
use App\Services\ISaleService;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    protected IBranchService $branchService;
    protected ISaleService $saleService;
    protected IBranchInventoryService $branchInventoryService;

    public function __construct(IBranchService $branchService , ISaleService $saleService , IBranchInventoryService $branchInventoryService)
    {
        $this->branchInventoryService = $branchInventoryService;
        $this->branchService = $branchService;
        $this->saleService = $saleService;
    }
    public function getBranchInvoices()
    {
        $branches = $this->branchService->getAllBranches();
        return view('admin.reports.invoices',compact('branches'));
    }

    public function getInvoices(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $invoices = $this->saleService->getSaleByBranchId(
            $request->branch_id,
            $request->start_date,
            $request->end_date
        );

        return response()->json(['data' => $invoices]);
    }

    public function getSoldProductsCount()
    {
        $branches = $this->branchService->getAllBranches();

        return view('admin.reports.product-quantity', compact(['branches']));
    }

    public function getProductCount(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'product_id' => 'nullable|exists:products,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $soldProduct = $this->saleService->getSoldProductQuantity(
            $request->branch_id,
            $request->product_id,
            $request->start_date,
            $request->end_date
        );

        return response()->json(['data' => $soldProduct]);
    }

    public function getProductsByBranch(Request $request)
    {
        $request->validate(['branch_id' => 'required|exists:branches,id']);

        $products = $this->branchInventoryService->getAllInventoryProducts($request->branch_id);

        return response()->json(['data' => $products]);
    }
}
