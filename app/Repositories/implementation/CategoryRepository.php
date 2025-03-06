<?php
namespace App\Repositories\implementation;

use App\Models\Category;
use App\Repositories\ICategory;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements ICategory
{
    /**
     * Get all categories with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return Category::paginate(PAGINATE_COUNT);
    }

    /**
     * Find a category by ID.
     *
     * @param int $id
     * @return Category|null
     */
    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }

    /**
     * Create a new category and return its ID.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int
    {
        return Category::create($data)->id;
    }

    /**
     * Update a category by ID.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool
    {
        return Category::where('id', $id)->update($data) > 0;
    }

    /**
     * Delete a category by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return Category::destroy($id) > 0;
    }

    /**
     * Check if a category exists by ID.
     *
     * @param int $id
     * @return bool
     */
    public function isExists(int $id): bool
    {
        return Category::whereKey($id)->exists();
    }
}
