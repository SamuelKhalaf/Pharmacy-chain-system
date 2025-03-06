<?php

namespace App\Services\implementation;

use App\Models\Product;
use App\Repositories\IBranchInventory;
use App\Repositories\IProduct;
use App\Services\IProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class ProductService
 * Manages product-related operations.
 */
class ProductService implements IProductService
{
    /**
     * @var IProduct
     */
    protected IProduct $productRepository;

    /**
     * @var IBranchInventory
     */
    protected IBranchInventory $branchInventoryRepository;

    /**
     * ProductService constructor.
     *
     * @param IProduct $productRepository
     * @param IBranchInventory $branchInventoryRepository
     */
    public function __construct(IProduct $productRepository, IBranchInventory $branchInventoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->branchInventoryRepository = $branchInventoryRepository;
    }

    /**
     * Retrieve all products.
     *
     * @return LengthAwarePaginator
     */
    public function getAllProducts(): LengthAwarePaginator
    {
        return $this->productRepository->getAll();
    }

    /**
     * Retrieve a specific product by its ID.
     *
     * @param int $id
     * @return Product|null
     */
    public function getOneProduct(int $id): ?Product
    {
        return $this->productRepository->findById($id);
    }

    /**
     * Create a new product.
     *
     * @param array $data
     * @return int
     */
    public function createProduct(array $data): int
    {
        return $this->productRepository->create($data);
    }

    /**
     * Update an existing product by its ID.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateProduct(array $data, int $id): bool
    {
        return $this->productRepository->update($data, $id);
    }

    /**
     * Delete a product by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteProduct(int $id): bool
    {
        try {
            DB::beginTransaction();

            $this->branchInventoryRepository->deleteSpecificProductsFromAllInventories($id);
            $this->productRepository->delete($id);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Delete all products associated with a specific category.
     *
     * @param int $category_id
     * @return bool
     */
    public function deleteProductByCategoryId(int $category_id): bool
    {
        try {
            DB::beginTransaction();

            $productIds = $this->productRepository->deleteProductsByCategoryId($category_id);
            $this->branchInventoryRepository->deleteSpecificProductsFromAllInventories($productIds);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Get the top-selling products for a specific year.
     *
     * @param int $year
     * @param int $limit Default is 5
     * @return Collection
     */
    public function getTopSellingProducts(int $year, int $limit = 5): Collection
    {
        return $this->productRepository->getTopSellingProducts($year, $limit);
    }
}
