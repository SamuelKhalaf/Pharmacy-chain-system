<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Services\ICategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    protected ICategoryService $categoryService;

    /**
     * @param ICategoryService $categoryService
     */
    public function __construct(ICategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $categories = $this->categoryService->getAllCategories();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @return RedirectResponse
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $created = $this->categoryService->createCategory($data);
        if (!$created) {
            return redirect()->route('category.create')->with(['error' => 'An error occurred while saving the category data']);
        }
        return redirect()->route('category.index')->with(['success' => 'Category data saved successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        $category = $this->categoryService->getOneCategory($id);
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(CategoryRequest $request, string $id): RedirectResponse
    {
        $data = $request->validated();
        $updated = $this->categoryService->updateCategory($data, $id);
        if (!$updated) {
            return redirect()->route('category.edit')->with(['error' => 'An error occurred while updating the category data']);
        }
        return redirect()->route('category.index')->with(['success' => 'Category data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $deleted = $this->categoryService->deleteCategory($id);
        if (!$deleted) {
            return redirect()->route('category.index')->with(['error' => 'An error occurred while deleting the category data']);
        }
        return redirect()->route('category.index')->with(['success' => 'Category data deleted successfully']);
    }
}
