<?php
namespace App\Repositories\implementation;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Repositories\IBranchInventory;
use App\Repositories\ISale;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class SaleRepository implements ISale
{
    protected IBranchInventory $branchInventoryRepository;

    /**
     * SaleRepository constructor.
     *
     * @param IBranchInventory $branchInventoryRepository
     */
    public function __construct(IBranchInventory $branchInventoryRepository)
    {
        $this->branchInventoryRepository = $branchInventoryRepository;
    }

    /**
     * Get all sales with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return Sale::query()
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->join('branches', 'sales.branch_id', '=', 'branches.id')
            ->select([
                'sales.id',
                'sales.total_price',
                'sales.created_at',
                'users.name as customer_name',
                'branches.name as branch_name'
            ])
            ->paginate(PAGINATE_COUNT);
    }

    /**
     * Find sales by branch ID with optional date range.
     *
     * @param int $branch_id
     * @param string|null $start_date
     * @param string|null $end_date
     * @return Collection
     */
    public function findByBranchId(int $branch_id, ?string $start_date = null, ?string $end_date = null): Collection
    {
        $query = Sale::query()
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->join('branches', 'sales.branch_id', '=', 'branches.id')
            ->select([
                'sales.id',
                'sales.total_price',
                'sales.created_at',
                'users.name as customer_name',
                'branches.name as branch_name'
            ])
            ->where('sales.branch_id', $branch_id);

        if ($start_date && $end_date) {
            $query->whereBetween('sales.created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        return $query->get();
    }

    /**
     * Get all sale items for a specific sale.
     *
     * @param int $sale_id
     * @return Collection
     */
    public function getSpecificSaleItems(int $sale_id): Collection
    {
        return Sale::query()
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select([
                'sale_items.quantity',
                'sale_items.price',
                'products.name as product_name'
            ])
            ->where('sales.id', $sale_id)
            ->get();
    }

    /**
     * Create a new sale transaction.
     *
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'branch_id' => $data['branch_id'],
                'user_id' => $data['user_id'],
                'total_price' => $data['total_price'],
            ]);

            foreach ($data['product_id'] as $index => $product_id) {
                $inventoryProduct = $this->branchInventoryRepository->getSpecificInventoryProduct($data['branch_id'], $product_id);

                $this->branchInventoryRepository->reduceProductsFromInventory($data['branch_id'], $product_id, $data['quantity'][$index]);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product_id,
                    'quantity' => $data['quantity'][$index],
                    'price' => $inventoryProduct->price,
                ]);
            }

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Update a sale (method not implemented).
     *
     * @param array $data
     * @param int $id
     * @return void
     */
    public function update(array $data, int $id): void {}

    /**
     * Delete a sale and its associated sale items.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        if ($this->isExists($id)) {
            try {
                DB::beginTransaction();

                SaleItem::where('sale_id', $id)->delete();
                Sale::where('id', $id)->delete();

                DB::commit();
                return true;
            } catch (Exception $exception) {
                DB::rollBack();
                return false;
            }
        }
        return false;
    }

    /**
     * Get the count of sales for each branch in a given month and year.
     *
     * @param int $month
     * @param int $year
     * @return \Illuminate\Support\Collection
     */
    public function getBranchSalesCountByMonth(int $month, int $year): \Illuminate\Support\Collection
    {
        return DB::table('sales')
            ->selectRaw('branch_id, COUNT(*) as count')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('branch_id')
            ->get();
    }

    /**
     * Get the total quantity of a sold product in a given branch with optional date range.
     *
     * @param int $branch_id
     * @param int $product_id
     * @param string|null $start_date
     * @param string|null $end_date
     * @return \Illuminate\Support\Collection
     */
    public function getSoldProductQuantity(int $branch_id, int $product_id, ?string $start_date = null, ?string $end_date = null): \Illuminate\Support\Collection
    {
        $query = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select([
                'products.id',
                'products.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity_sold')
            ])
            ->where('sales.branch_id', $branch_id)
            ->where('sale_items.product_id', $product_id)
            ->groupBy('products.id', 'products.name');

        if ($start_date && $end_date) {
            $query->whereBetween('sales.created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        return $query->get();
    }

    /**
     * Check if a sale exists by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function isExists(int $id): bool
    {
        return Sale::where('id', $id)->exists();
    }
}
