<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBranchInventoryRequest;
use App\Http\Requests\UpdateBranchInventoryRequest;
use App\Models\BranchInventory;
use App\Services\IBranchInventoryService;
use App\Services\IBranchService;
use App\Services\IProductService;
use Illuminate\Http\Request;

class BranchInventoryController extends Controller
{
    protected IBranchInventoryService $branchInventoryService;
    protected IBranchService $branchService;
    protected IProductService $productService;

    public function __construct(IBranchInventoryService $branchInventoryService,IBranchService $branchService,IProductService $productService)
    {
        $this->branchInventoryService = $branchInventoryService;
        $this->branchService = $branchService;
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = $this->branchService->getAllBranches();
        return view('admin.inventory.index',compact('branches'));
    }

    // ajax method
    public function allInventoryProducts(Request $request)
    {
        $branchInventory = $this->branchInventoryService->getAllInventoryProducts($request->branch_id);
        return response()->json(['data' => $branchInventory]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $newBranches = $this->branchService->getNewBranches();
        $products = $this->productService->getAllProducts();
        return view('admin.inventory.create',compact(['newBranches', 'products']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBranchInventoryRequest $request)
    {
        $data = $request->validated();
        $created = $this->branchInventoryService->storeNewInventoryProducts($data);
        if ($created) {
            return redirect()->route('inventory.index')->with('success', 'Inventory Product/s stored successfully.');
        } else {
            return redirect()->route('inventory.create')->with('error', 'An error occurred while store the Inventory product/s data');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($branch_id,$product_id)
    {
        $inventory = $this->branchInventoryService->getOneInventoryProduct($branch_id,$product_id);
        return view('admin.inventory.view',compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($branch_id,$product_id)
    {
        $inventory = $this->branchInventoryService->getOneInventoryProduct($branch_id,$product_id);
        return view('admin.inventory.edit',compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchInventoryRequest $request, $branch_id,$product_id)
    {
        $data = $request->validated();

        $updated =$this->branchInventoryService->updateSpecificInventoryProduct($data,$branch_id,$product_id);
        if ($updated) {
            return redirect()->route('inventory.index')->with('success', 'Inventory Product updated successfully.');
        } else {
            return redirect()->route('inventory.edit')->with('error', 'An error occurred while update the Inventory product data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($branch_id,$product_id)
    {
        $deleted = $this->branchInventoryService->deleteSpecificInventoryProduct($branch_id,$product_id);
        if ($deleted) {
            return redirect()->route('inventory.index')->with('success', 'Inventory Product deleted successfully.');
        } else {
            return redirect()->route('inventory.index')->with('error', 'An error occurred while delete the Inventory product data');
        }
    }
}
