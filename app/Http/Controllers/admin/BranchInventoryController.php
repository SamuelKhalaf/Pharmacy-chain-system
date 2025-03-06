<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBranchInventoryRequest;
use App\Http\Requests\GetBranchInventoryRequest;
use App\Http\Requests\UpdateBranchInventoryRequest;
use App\Services\IBranchInventoryService;
use App\Services\IBranchService;
use App\Services\IProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BranchInventoryController extends Controller
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
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $branches = $this->branchService->getOldBranches();
        return view('admin.inventory.index', compact('branches'));
    }

    /**
     * Fetch all inventory products for a branch (AJAX method).
     *
     * @param GetBranchInventoryRequest $request
     * @return JsonResponse
     */
    public function allInventoryProducts(GetBranchInventoryRequest $request): JsonResponse
    {
        $branchInventory = $this->branchInventoryService->getAllInventoryProducts($request->branch_id);
        return response()->json(['data' => $branchInventory]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $branches = $this->branchService->getAllBranches();
        $products = $this->productService->getAllProducts();
        return view('admin.inventory.create', compact(['branches', 'products']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateBranchInventoryRequest $request
     * @return RedirectResponse
     */
    public function store(CreateBranchInventoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $created = $this->branchInventoryService->storeNewInventoryProducts($data);
        if ($created) {
            return redirect()->route('inventory.index')->with('success', 'Inventory Product/s stored successfully.');
        }
        return redirect()->route('inventory.create')->with('error', 'An error occurred while storing the Inventory product/s data');
    }

    /**
     * Display the specified resource.
     *
     * @param string $branch_id
     * @param string $product_id
     * @return View
     */
    public function show(string $branch_id, string $product_id): View
    {
        $inventory = $this->branchInventoryService->getOneInventoryProduct($branch_id, $product_id);
        return view('admin.inventory.view', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $branch_id
     * @param string $product_id
     * @return View
     */
    public function edit(string $branch_id, string $product_id): View
    {
        $inventory = $this->branchInventoryService->getOneInventoryProduct($branch_id, $product_id);
        return view('admin.inventory.edit', compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBranchInventoryRequest $request
     * @param string $branch_id
     * @param string $product_id
     * @return RedirectResponse
     */
    public function update(UpdateBranchInventoryRequest $request, string $branch_id, string $product_id): RedirectResponse
    {
        $data = $request->validated();
        $updated = $this->branchInventoryService->updateSpecificInventoryProduct($data, $branch_id, $product_id);
        if ($updated) {
            return redirect()->route('inventory.index')->with('success', 'Inventory Product updated successfully.');
        }
        return redirect()->route('inventory.edit')->with('error', 'An error occurred while updating the Inventory product data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $branch_id
     * @param string $product_id
     * @return RedirectResponse
     */
    public function destroy(string $branch_id, string $product_id): RedirectResponse
    {
        $deleted = $this->branchInventoryService->deleteSpecificInventoryProduct($branch_id, $product_id);
        if ($deleted) {
            return redirect()->route('inventory.index')->with('success', 'Inventory Product deleted successfully.');
        }
        return redirect()->route('inventory.index')->with('error', 'An error occurred while deleting the Inventory product data');
    }
}
