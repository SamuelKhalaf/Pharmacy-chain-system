<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Services\IBranchInventoryService;
use App\Services\ICategoryService;
use App\Services\IProductService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    protected IProductService $productService;
    protected ICategoryService $categoryService;
    protected IBranchInventoryService $branchInventoryService;

    /**
     * @param IProductService $productService
     * @param ICategoryService $categoryService
     * @param IBranchInventoryService $branchInventoryService
     */
    public function __construct(
        IProductService $productService,
        ICategoryService $categoryService,
        IBranchInventoryService $branchInventoryService
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->branchInventoryService = $branchInventoryService;
    }

    /**
     * Display a listing of the products.
     *
     * @return Factory|Application|View
     */
    public function index(): Factory|Application|View
    {
        $products = $this->productService->getAllProducts();
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return Factory|Application|View
     */
    public function create(): Factory|Application|View
    {
        $categories = $this->categoryService->getAllCategories();
        return view('admin.product.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param ProductRequest $request
     * @return RedirectResponse
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $created = $this->productService->createProduct($data);
        if (!$created) {
            return redirect()->route('product.create')->with(['error' => 'An error occurred while saving the product data']);
        }
        return redirect()->route('product.index')->with(['success' => 'Product data saved successfully']);
    }

    /**
     * Display the specified product.
     *
     * @param string $id
     * @return Factory|Application|View
     */
    public function show(string $id): Factory|Application|View
    {
        $product = $this->productService->getOneProduct($id);
        return view('admin.product.view', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param string $id
     * @return Factory|Application|View
     */
    public function edit(string $id): Factory|Application|View
    {
        $product = $this->productService->getOneProduct($id);
        $categories = $this->categoryService->getAllCategories();
        return view('admin.product.edit', compact(['product', 'categories']));
    }

    /**
     * Update the specified product in storage.
     *
     * @param ProductRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(ProductRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();
        $updated = $this->productService->updateProduct($data, $id);
        if (!$updated) {
            return redirect()->route('product.edit', ['id' => $id])->with(['error' => 'An error occurred while updating the product data']);
        }
        return redirect()->route('product.index')->with(['success' => 'Product data updated successfully']);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $deleted = $this->productService->deleteProduct($id);
        if ($deleted) {
            return redirect()->route('product.index')->with(['success' => 'Product data deleted successfully']);
        } else {
            return redirect()->route('product.index')->with(['error' => 'An error occurred while deleting the product data']);
        }
    }
}
