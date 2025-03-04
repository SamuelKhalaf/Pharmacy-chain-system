<?php
namespace App\Repositories\implementation;

use App\Models\Product;
use App\Repositories\IProduct;
use Illuminate\Support\Facades\DB;

class ProductRepository implements IProduct
{
    public function getAll()
    {
        return Product::paginate(PAGINATE_COUNT);
    }
    public function findById($id)
    {
        if ($this->isExists($id)){
            return Product::where('id',$id)->first();
        }else{
            return false;
        }
    }

    public function create(array $data)
    {
        return Product::create($data)->id;
    }

    public function update(array $data, $id)
    {
        if ($this->isExists($id)){
            return Product::where('id',$id)->update($data);
        }else{
            return false;
        }
    }

    public function delete($id)
    {
        if ($this->isExists($id)){
            return Product::where('id',$id)->delete();
        }else{
            return false;
        }
    }
    public function deleteProductsByCategoryId($category_id)
    {
        $productIds = Product::where('category_id', $category_id)->pluck('id')->toArray();

        Product::where('category_id', $category_id)->delete();

        return $productIds;
    }

    public function isExists($id)
    {
        return Product::where('id',$id)->exists();
    }

    public function getTopSellingProducts($year, $limit = 5)
    {
        return Product::select(
            'products.id',
            'products.name',
            DB::raw('SUM(sale_items.quantity) as total_sold'),
            DB::raw('(SELECT price FROM sale_items WHERE sale_items.product_id = products.id ORDER BY sale_items.sale_id DESC LIMIT 1) as price'))
            ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereYear('sales.created_at', $year)
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }
}
