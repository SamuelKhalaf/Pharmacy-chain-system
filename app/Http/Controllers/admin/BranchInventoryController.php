<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchInventoryRequest;
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
//        $inventories = $this->branchInventoryService->getAllInventories();
//        $branches = $this->branchService->getAllBranches();
//        $products = $this->productService->getAllProducts();
        return view('admin.inventory.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = $this->branchService->getAllBranches();
        $products = $this->productService->getAllProducts();
        return view('admin.inventory.create',compact(['branches', 'products']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchInventoryRequest $request)
    {
        $data = $request->validated();
        $created = $this->branchInventoryService->storeInventoryProducts($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
