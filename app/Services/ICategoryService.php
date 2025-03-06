<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface for category service.
 */
interface ICategoryService
{
    /**
     * Get all categories.
     *
     * @return LengthAwarePaginator
     */
    public function getAllCategories(): LengthAwarePaginator;

    /**
     * Get a specific category by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getOneCategory(int $id): mixed;

    /**
     * Create a new category.
     *
     * @param array $data
     * @return int
     */
    public function createCategory(array $data): int;

    /**
     * Update an existing category.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateCategory(array $data, int $id): bool;

    /**
     * Delete a category.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCategory(int $id): bool;
}
