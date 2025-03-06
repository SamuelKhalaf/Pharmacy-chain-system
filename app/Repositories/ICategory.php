<?php
namespace App\Repositories;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

interface ICategory
{
    /**
     * Get all categories with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Find a category by ID.
     *
     * @param int $id
     * @return Category|null
     */
    public function findById(int $id): ?Category;

    /**
     * Create a new category.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int;

    /**
     * Update a category by ID.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool;

    /**
     * Delete a category by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
