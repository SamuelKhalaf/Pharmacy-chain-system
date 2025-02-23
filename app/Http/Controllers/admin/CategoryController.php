<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Services\ICategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected ICategoryService $categoryService;
    public function __construct(ICategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();

        $created = $this->categoryService->createCategory($data);
        if (!$created){
            return redirect()->route('category.create')->with(['error' => 'an error occurred while save the category data']);
        }
        return redirect()->route('category.index')->with(['success' => 'category data saved successfully']);

    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category =$this->categoryService->getOneCategory($id);
        return view('admin.category.edit' , compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $data = $request->validated();
        $updated = $this->categoryService->updateCategory($data,$id);
        if (!$updated){
            return redirect()->route('category.edit')->with(['error' => 'an error occurred while update the category data']);
        }
        return redirect()->route('category.index')->with(['success' => 'category data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->categoryService->deleteCategory($id);
        if (!$deleted){
            return redirect()->route('category.index')->with(['error' => 'an error occurred while delete the category data']);
        }
        return redirect()->route('category.index')->with(['success' => 'category data deleted successfully']);
    }
}
