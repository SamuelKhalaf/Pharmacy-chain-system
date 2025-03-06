<?php

namespace App\Services\implementation;

use App\Repositories\ICategory;
use App\Services\ICategoryService;
use App\Services\IProductService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Service class for managing categories.
 */
class CategoryService implements ICategoryService
{
    /**
     * @var ICategory
     */
    protected ICategory $categoryRepository;

    /**
     * @var IProductService
     */
    protected IProductService $productService;

    /**
     * CategoryService constructor.
     *
     * @param ICategory $categoryRepository
     * @param IProductService $productService
     */
    public function __construct(ICategory $categoryRepository, IProductService $productService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productService = $productService;
    }

    /**
     * Get all categories.
     *
     * @return LengthAwarePaginator
     */
    public function getAllCategories(): LengthAwarePaginator
    {
        return $this->categoryRepository->getAll();
    }

    /**
     * Get a specific category by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getOneCategory(int $id): mixed
    {
        return $this->categoryRepository->findById($id);
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return int
     */
    public function createCategory(array $data): int
    {
        return $this->categoryRepository->create($data);
    }

    /**
     * Update an existing category.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateCategory(array $data, int $id): bool
    {
        return $this->categoryRepository->update($data, $id);
    }

    /**
     * Delete a category and all its associated products.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCategory(int $id): bool
    {
        try {
            DB::beginTransaction();

            // Delete all products in this category
            $this->productService->deleteProductByCategoryId($id);

            // Delete the category itself
            $this->categoryRepository->delete($id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
