<?php
namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Product;

interface IProduct
{
    /**
     * Get all products with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Find a product by ID.
     *
     * @param int $id
     * @return Product|null
     */
    public function findById(int $id): ?Product;

    /**
     * Create a new product and return its ID.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int;

    /**
     * Update a product by ID.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool;

    /**
     * Delete a product by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Delete all products by category ID and return their IDs.
     *
     * @param int $category_id
     * @return array
     */
    public function deleteProductsByCategoryId(int $category_id): array;

    /**
     * Get top-selling products for a specific year with a limit.
     *
     * @param int $year
     * @param int $limit
     * @return Collection
     */
    public function getTopSellingProducts(int $year, int $limit): Collection;
}
