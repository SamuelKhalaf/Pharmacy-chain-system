<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Services\IBranchInventoryService;
use App\Services\ICategoryService;
use App\Services\IProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    protected IProductService $productService;
    protected ICategoryService $categoryService;
    protected IBranchInventoryService $branchInventoryService;

    public function __construct(IProductService $productService , ICategoryService $categoryService
        ,IBranchInventoryService $branchInventoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->branchInventoryService = $branchInventoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->productService->getAllProducts();
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('admin.product.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        $created = $this->productService->createProduct($data);
        if (!$created){
            return redirect()->route('product.create')->with(['error' => 'an error occurred while save the product data']);
        }
        return redirect()->route('product.index')->with(['success' => 'product data saved successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->productService->getOneProduct($id);
        return view('admin.product.view',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = $this->productService->getOneProduct($id);
        $categories = $this->categoryService->getAllCategories();
        return view('admin.product.edit',compact(['product','categories']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        $data = $request->validated();
        $updated = $this->productService->updateProduct($data , $id);
        if (!$updated){
            return redirect()->route('product.update')->with(['error' => 'an error occurred while update the product data']);
        }
        return redirect()->route('product.index')->with(['success' => 'product data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->productService->deleteProduct($id);
        if ($deleted){
            return redirect()->route('product.index')->with(['success' => 'product data deleted successfully']);
        }else{
            return redirect()->route('product.index')->with(['error' => 'an error occurred while delete the product data']);
        }
    }
}
