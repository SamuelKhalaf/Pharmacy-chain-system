<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface IProductService
 * Defines the contract for product-related services.
 */
interface IProductService
{
    /**
     * Retrieve all products.
     *
     * @return LengthAwarePaginator
     */
    public function getAllProducts(): LengthAwarePaginator;

    /**
     * Retrieve a specific product by its ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getOneProduct(int $id): mixed;

    /**
     * Create a new product.
     *
     * @param array $data
     * @return mixed
     */
    public function createProduct(array $data): mixed;

    /**
     * Update an existing product by its ID.
     *
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function updateProduct(array $data, int $id): mixed;

    /**
     * Delete a product by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteProduct(int $id): bool;

    /**
     * Delete all products associated with a specific category.
     *
     * @param int $category_id
     * @return bool
     */
    public function deleteProductByCategoryId(int $category_id): bool;

    /**
     * Get the top-selling products for a specific year.
     *
     * @param int $year
     * @param int $limit Default is 5
     * @return Collection
     */
    public function getTopSellingProducts(int $year, int $limit = 5): Collection;
}
