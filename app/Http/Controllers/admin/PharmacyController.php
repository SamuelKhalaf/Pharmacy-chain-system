<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBranchInventoryRequest;
use App\Services\IBranchInventoryService;
use App\Services\IBranchService;
use App\Services\IProductService;
use Illuminate\Http\Request;

class PharmacyController extends Controller
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
    public function index()
    {
        $authAdminBranchId = auth()->user()->branch_id;
        $inventoryProducts = $this->branchInventoryService->getAllInventoryProducts($authAdminBranchId);
        return view('admin.pharmacy.index',compact('inventoryProducts'));
    }


    /**
     * Display the specified resource.
     */
    public function show($branch_id,$product_id)
    {
        $inventory = $this->branchInventoryService->getOneInventoryProduct($branch_id,$product_id);
        return view('admin.pharmacy.view',compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($branch_id,$product_id)
    {
        $inventory = $this->branchInventoryService->getOneInventoryProduct($branch_id,$product_id);
        return view('admin.pharmacy.edit',compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchInventoryRequest $request, $branch_id,$product_id)
    {
        $data = $request->validated();

        $updated =$this->branchInventoryService->updateSpecificInventoryProduct($data,$branch_id,$product_id);
        if ($updated) {
            return redirect()->route('pharmacy.index')->with('success', 'Pharmacy Product updated successfully.');
        } else {
            return redirect()->route('pharmacy.edit')->with('error', 'An error occurred while update the Pharmacy product data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($branch_id,$product_id)
    {
        $deleted = $this->branchInventoryService->deleteSpecificInventoryProduct($branch_id,$product_id);
        if ($deleted) {
            return redirect()->route('pharmacy.index')->with('success', 'Pharmacy Product deleted successfully.');
        } else {
            return redirect()->route('pharmacy.index')->with('error', 'An error occurred while delete the Pharmacy product data');
        }
    }
}
