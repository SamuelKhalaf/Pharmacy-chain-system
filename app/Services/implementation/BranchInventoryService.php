<?php

namespace App\Services\implementation;

use App\Repositories\IBranchInventory;
use App\Repositories\IProduct;
use App\Services\IBranchInventoryService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Service class for managing branch inventory operations.
 */
class BranchInventoryService implements IBranchInventoryService
{
    /**
     * @var IBranchInventory
     */
    protected IBranchInventory $branchInventoryRepository;

    /**
     * @var IProduct
     */
    protected IProduct $productRepository;

    /**
     * BranchInventoryService constructor.
     *
     * @param IBranchInventory $branchInventoryRepository
     * @param IProduct $productRepository
     */
    public function __construct(IBranchInventory $branchInventoryRepository, IProduct $productRepository)
    {
        $this->branchInventoryRepository = $branchInventoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Get all inventory products for a specific branch.
     *
     * @param int $branch_id
     * @return Collection
     */
    public function getAllInventoryProducts(int $branch_id): Collection
    {
        return $this->branchInventoryRepository->getAllInventoryProducts($branch_id);
    }

    /**
     * Get all critical products in inventory.
     *
     * @return Collection
     */
    public function getCriticalProducts(): Collection
    {
        return $this->branchInventoryRepository->getCriticalProducts();
    }

    /**
     * Get a specific inventory product by branch and product ID.
     *
     * @param int $branch_id
     * @param int $product_id
     * @return mixed
     */
    public function getOneInventoryProduct(int $branch_id, int $product_id): mixed
    {
        return $this->branchInventoryRepository->getSpecificInventoryProduct($branch_id, $product_id);
    }

    /**
     * Store new inventory products.
     *
     * @param array $data
     * @return bool
     */
    public function storeNewInventoryProducts(array $data): bool
    {
        return $this->branchInventoryRepository->storeNewInventoryProducts($data);
    }

    /**
     * Update a specific inventory product.
     *
     * @param array $data
     * @param int $branch_id
     * @param int $product_id
     * @return bool
     */
    public function updateSpecificInventoryProduct(array $data, int $branch_id, int $product_id): bool
    {
        return $this->branchInventoryRepository->updateSpecificInventoryProduct($data, $branch_id, $product_id);
    }

    /**
     * Delete all inventory products for a branch.
     *
     * @param int $branch_id
     * @return bool
     */
    public function deleteAllInventoryProducts(int $branch_id): bool
    {
        return $this->branchInventoryRepository->deleteAllInventoryProducts($branch_id);
    }

    /**
     * Delete a specific product from all inventories.
     *
     * @param int $product_id
     * @return bool
     */
    public function deleteSpecificProductFromAllInventories(int $product_id): bool
    {
        return $this->branchInventoryRepository->deleteSpecificProductsFromAllInventories($product_id);
    }

    /**
     * Delete a specific product from a branch inventory.
     *
     * @param int $branch_id
     * @param int $product_id
     * @return bool
     */
    public function deleteSpecificInventoryProduct(int $branch_id, int $product_id): bool
    {
        return $this->branchInventoryRepository->deleteSpecificInventoryProduct($branch_id, $product_id);
    }

    /**
     * Transfer products between two branch inventories.
     *
     * @param array $data Must contain:
     *                    - from_branch_id (int)
     *                    - to_branch_id (int)
     *                    - product_id (array<int>)
     *                    - quantity (array<int>)
     * @return bool
     */
    public function transferProductsBetweenInventories(array $data): bool
    {
        try {
            DB::beginTransaction();
            foreach ($data['product_id'] as $index => $product_id) {
                // Add products to destination branch inventory
                $this->branchInventoryRepository->addProductsToInventory(
                    $data['to_branch_id'],
                    $product_id,
                    $data['quantity'][$index]
                );

                // Reduce products from source branch inventory
                $this->branchInventoryRepository->reduceProductsFromInventory(
                    $data['from_branch_id'],
                    $product_id,
                    $data['quantity'][$index]
                );
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
