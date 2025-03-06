<?php

namespace App\Services;


use Illuminate\Support\Collection;

interface IBranchInventoryService
{
    /**
     * Get all inventory products for a specific branch.
     *
     * @param int $branch_id
     * @return Collection
     */
    public function getAllInventoryProducts(int $branch_id): Collection;

    /**
     * Get all critical products in inventory.
     *
     * @return Collection
     */
    public function getCriticalProducts(): Collection;

    /**
     * Get a specific inventory product by branch and product ID.
     *
     * @param int $branch_id
     * @param int $product_id
     * @return mixed
     */
    public function getOneInventoryProduct(int $branch_id, int $product_id): mixed;

    /**
     * Store new inventory products.
     *
     * @param array $data
     * @return bool
     */
    public function storeNewInventoryProducts(array $data): bool;

    /**
     * Update a specific inventory product.
     *
     * @param array $data
     * @param int $branch_id
     * @param int $product_id
     * @return bool
     */
    public function updateSpecificInventoryProduct(array $data, int $branch_id, int $product_id): bool;

    /**
     * Delete all inventory products for a branch.
     *
     * @param int $branch_id
     * @return bool
     */
    public function deleteAllInventoryProducts(int $branch_id): bool;

    /**
     * Delete a specific product from all inventories.
     *
     * @param int $product_id
     * @return bool
     */
    public function deleteSpecificProductFromAllInventories(int $product_id): bool;

    /**
     * Delete a specific product from a branch inventory.
     *
     * @param int $branch_id
     * @param int $product_id
     * @return bool
     */
    public function deleteSpecificInventoryProduct(int $branch_id, int $product_id): bool;

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
    public function transferProductsBetweenInventories(array $data): bool;
}
