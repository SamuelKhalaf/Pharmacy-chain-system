<?php
namespace App\Repositories;

use App\Models\BranchInventory;
use Illuminate\Support\Collection;

interface IBranchInventory
{
    /**
     * Get all inventory branch IDs.
     *
     * @return array
     */
    public function getAllInventoriesByBranchID(): array;

    /**
     * Get a list of critical products (products with low stock).
     *
     * @return Collection
     */
    public function getCriticalProducts(): Collection;

    /**
     * Get all inventory products for a specific branch.
     *
     * @param int $branch_id
     * @return Collection
     */
    public function getAllInventoryProducts(int $branch_id): Collection;

    /**
     * Get a specific inventory product in a branch.
     *
     * @param int $branch_id
     * @param int $product_id
     * @return object|null
     */
    public function getSpecificInventoryProduct(int $branch_id, int $product_id): ?object;

    /**
     * Store new inventory products.
     *
     * @param array $data
     * @return bool|array
     */
    public function storeNewInventoryProducts(array $data): bool|array;

    /**
     * Update a specific inventory product in a branch.
     *
     * @param array $data
     * @param int $branch_id
     * @param int $product_id
     * @return bool|int
     */
    public function updateSpecificInventoryProduct(array $data, int $branch_id, int $product_id): bool|int;

    /**
     * Delete all inventory products for a specific branch.
     *
     * @param int $branch_id
     * @return bool
     */
    public function deleteAllInventoryProducts(int $branch_id): bool;

    /**
     * Delete specific products from all inventories.
     *
     * @param array|string $productIds
     * @return int
     */
    public function deleteSpecificProductsFromAllInventories(array|string $productIds): int;

    /**
     * Delete a specific inventory product in a branch.
     *
     * @param int $branch_id
     * @param int $product_id
     * @return bool
     */
    public function deleteSpecificInventoryProduct(int $branch_id, int $product_id): bool;

    /**
     * Add products to a specific branch's inventory.
     *
     * @param int $to_branch_id
     * @param int $product_id
     * @param int $quantity
     * @return bool
     */
    public function addProductsToInventory(int $to_branch_id, int $product_id, int $quantity): bool;

    /**
     * Reduce products from a specific branch's inventory.
     *
     * @param int $from_branch_id
     * @param int $product_id
     * @param int $quantity
     * @return bool
     */
    public function reduceProductsFromInventory(int $from_branch_id, int $product_id, int $quantity): bool;
}
