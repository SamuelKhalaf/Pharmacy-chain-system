<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditPharmacyRequest;
use App\Services\IBranchInventoryService;
use App\Services\IBranchService;
use App\Services\IProductService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class PharmacyController extends Controller
{
    protected IBranchInventoryService $branchInventoryService;
    protected IBranchService $branchService;
    protected IProductService $productService;

    /**
     * @param IBranchInventoryService $branchInventoryService
     * @param IBranchService $branchService
     * @param IProductService $productService
     */
    public function __construct(
        IBranchInventoryService $branchInventoryService,
        IBranchService $branchService,
        IProductService $productService
    ) {
        $this->branchInventoryService = $branchInventoryService;
        $this->branchService = $branchService;
        $this->productService = $productService;
    }

    /**
     * Display a listing of the pharmacy inventory products.
     *
     * @return Factory|Application|View
     */
    public function index(): Factory|Application|View
    {
        $authAdminBranchId = auth()->user()->branch_id;
        $inventoryProducts = $this->branchInventoryService->getAllInventoryProducts($authAdminBranchId);
        return view('admin.pharmacy.index', compact('inventoryProducts'));
    }

    /**
     * Display the specified inventory product.
     *
     * @param int $branch_id
     * @param int $product_id
     * @return Factory|Application|View
     */
    public function show(int $branch_id, int $product_id): Factory|Application|View
    {
        $inventory = $this->branchInventoryService->getOneInventoryProduct($branch_id, $product_id);
        return view('admin.pharmacy.view', compact('inventory'));
    }

    /**
     * Show the form for editing the specified inventory product.
     *
     * @param int $branch_id
     * @param int $product_id
     * @return Factory|Application|View
     */
    public function edit(int $branch_id, int $product_id): Factory|Application|View
    {
        $inventory = $this->branchInventoryService->getOneInventoryProduct($branch_id, $product_id);
        return view('admin.pharmacy.edit', compact('inventory'));
    }

    /**
     * Update the specified inventory product.
     *
     * @param EditPharmacyRequest $request
     * @param int $branch_id
     * @param int $product_id
     * @return RedirectResponse
     */
    public function update(EditPharmacyRequest $request, int $branch_id, int $product_id): RedirectResponse
    {
        $data = $request->validated();

        $updated = $this->branchInventoryService->updateSpecificInventoryProduct($data, $branch_id, $product_id);
        if ($updated) {
            return redirect()->route('pharmacy.index')->with('success', 'Pharmacy Product updated successfully.');
        } else {
            return redirect()->route('pharmacy.edit')->with('error', 'An error occurred while updating the Pharmacy product data');
        }
    }

    /**
     * Remove the specified inventory product.
     *
     * @param int $branch_id
     * @param int $product_id
     * @return RedirectResponse
     */
    public function destroy(int $branch_id, int $product_id): RedirectResponse
    {
        $deleted = $this->branchInventoryService->deleteSpecificInventoryProduct($branch_id, $product_id);
        if ($deleted) {
            return redirect()->route('pharmacy.index')->with('success', 'Pharmacy Product deleted successfully.');
        } else {
            return redirect()->route('pharmacy.index')->with('error', 'An error occurred while deleting the Pharmacy product data');
        }
    }
}
