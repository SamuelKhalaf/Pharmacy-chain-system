<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\IBranchInventoryService;
use App\Services\IBranchService;
use App\Services\ISaleService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    protected IBranchService $branchService;
    protected ISaleService $saleService;
    protected IBranchInventoryService $branchInventoryService;

    /**
     * @param IBranchService $branchService
     * @param ISaleService $saleService
     * @param IBranchInventoryService $branchInventoryService
     */
    public function __construct(
        IBranchService $branchService,
        ISaleService $saleService,
        IBranchInventoryService $branchInventoryService
    ) {
        $this->branchInventoryService = $branchInventoryService;
        $this->branchService = $branchService;
        $this->saleService = $saleService;
    }

    /**
     * Display the branch invoices report page.
     *
     * @return Factory|Application|View
     */
    public function getBranchInvoices(): Factory|Application|View
    {
        $branches = $this->branchService->getAllBranches();
        return view('admin.reports.invoices', compact('branches'));
    }

    /**
     * Get invoices for a specific branch within a date range.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getInvoices(Request $request): JsonResponse
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

    /**
     * Display the sold products count report page.
     *
     * @return Factory|Application|View
     */
    public function getSoldProductsCount(): Factory|Application|View
    {
        $branches = $this->branchService->getAllBranches();
        return view('admin.reports.product-quantity', compact(['branches']));
    }

    /**
     * Get the quantity of a specific product sold within a date range.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProductCount(Request $request): JsonResponse
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

    /**
     * Get all products available in a specific branch's inventory.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProductsByBranch(Request $request): JsonResponse
    {
        $request->validate(['branch_id' => 'required|exists:branches,id']);

        $products = $this->branchInventoryService->getAllInventoryProducts($request->branch_id);

        return response()->json(['data' => $products]);
    }
}
