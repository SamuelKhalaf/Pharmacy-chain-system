<?php
namespace App\Repositories\implementation;

use App\Models\Product;
use App\Repositories\IProduct;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProductRepository implements IProduct
{
    /**
     * Get all products with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return Product::paginate(PAGINATE_COUNT);
    }

    /**
     * Find a product by ID.
     *
     * @param int $id
     * @return Product|null
     */
    public function findById(int $id): ?Product
    {
        if ($this->isExists($id)) {
            return Product::where('id', $id)->first();
        }
        return null;
    }

    /**
     * Create a new product and return its ID.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int
    {
        return Product::create($data)->id;
    }

    /**
     * Update a product by ID.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool
    {
        if ($this->isExists($id)) {
            return Product::where('id', $id)->update($data) > 0;
        }
        return false;
    }

    /**
     * Delete a product by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        if ($this->isExists($id)) {
            return Product::where('id', $id)->delete() > 0;
        }
        return false;
    }

    /**
     * Delete all products by category ID and return their IDs.
     *
     * @param int $category_id
     * @return array
     */
    public function deleteProductsByCategoryId(int $category_id): array
    {
        $productIds = Product::where('category_id', $category_id)->pluck('id')->toArray();
        Product::where('category_id', $category_id)->delete();
        return $productIds;
    }

    /**
     * Check if a product exists by ID.
     *
     * @param int $id
     * @return bool
     */
    public function isExists(int $id): bool
    {
        return Product::where('id', $id)->exists();
    }

    /**
     * Get top-selling products for a specific year with a limit.
     *
     * @param int $year
     * @param int $limit
     * @return Collection
     */
    public function getTopSellingProducts(int $year, int $limit = 5): Collection
    {
        return Product::select(
            'products.id',
            'products.name',
            DB::raw('SUM(sale_items.quantity) as total_sold'),
            DB::raw('(SELECT price FROM sale_items WHERE sale_items.product_id = products.id ORDER BY sale_items.sale_id DESC LIMIT 1) as price')
        )
            ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereYear('sales.created_at', $year)
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }
}
