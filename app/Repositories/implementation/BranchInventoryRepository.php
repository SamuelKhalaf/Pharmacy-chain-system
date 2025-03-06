<?php
namespace App\Repositories\implementation;

use App\Models\BranchInventory;
use App\Repositories\IBranchInventory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BranchInventoryRepository implements IBranchInventory
{
    /**
     * Get all branch inventory IDs.
     *
     * @return array
     */
    public function getAllInventoriesByBranchID(): array
    {
        return BranchInventory::pluck('branch_id')->toArray();
    }

    /**
     * Get products that have reached their critical stock level.
     *
     * @return Collection
     */
    public function getCriticalProducts(): Collection
    {
        return DB::table('branch_inventory')
            ->join('products', 'branch_inventory.product_id', '=', 'products.id')
            ->join('branches', 'branch_inventory.branch_id', '=', 'branches.id')
            ->whereColumn('branch_inventory.quantity', '<=', 'branch_inventory.critical_level')
            ->select(
                'branch_inventory.*',
                'products.name as product_name',
                'branches.name as branch_name'
            )
            ->get();
    }

    /**
     * Get all inventory products for a specific branch.
     *
     * @param int $branch_id
     * @return Collection
     */
    public function getAllInventoryProducts(int $branch_id): Collection
    {
        return DB::table('branch_inventory')
            ->join('products', 'branch_inventory.product_id', '=', 'products.id')
            ->join('branches', 'branch_inventory.branch_id', '=', 'branches.id')
            ->where('branch_inventory.branch_id', $branch_id)
            ->select([
                'branch_inventory.*',
                'products.name as product_name',
                'products.id as product_id',
                'branches.name as branch_name'
            ])
            ->get();
    }

    /**
     * Get a specific inventory product by branch and product ID.
     *
     * @param int $branch_id
     * @param int $product_id
     * @return object|null
     */
    public function getSpecificInventoryProduct(int $branch_id, int $product_id): ?object
    {
        return BranchInventory::query()
            ->where('branch_id', $branch_id)
            ->where('product_id', $product_id)
            ->first();
    }

    /**
     * Store new inventory products.
     *
     * @param array $data
     * @return array|bool
     */
    public function storeNewInventoryProducts(array $data): array|bool
    {
        try {
            DB::beginTransaction();
            $newInventoryRecords = [];

            foreach ($data['product_id'] as $index => $product_id) {
                $inventory = BranchInventory::where('branch_id', $data['branch_id'])
                    ->where('product_id', $product_id)
                    ->first();

                if ($inventory) {
                    $inventory->increment('quantity', $data['quantity'][$index]);
                    $newInventoryRecords[] = $inventory;
                } else {
                    $newInventoryRecords[] = BranchInventory::create([
                        'branch_id'  => $data['branch_id'],
                        'product_id' => $product_id,
                        'quantity'   => $data['quantity'][$index],
                        'price'      => 0,
                    ]);
                }
            }

            DB::commit();
            return $newInventoryRecords;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Update a specific inventory product.
     *
     * @param array $data
     * @param int $branch_id
     * @param int $product_id
     * @return bool|int
     */
    public function updateSpecificInventoryProduct(array $data, int $branch_id, int $product_id): bool|int
    {
        return BranchInventory::query()
                ->where('branch_id', $branch_id)
                ->where('product_id', $product_id)
                ->update($data) > 0;
    }

    /**
     * Add products to a branch's inventory.
     *
     * @param int $to_branch_id
     * @param int $product_id
     * @param int $quantity
     * @return bool
     */
    public function addProductsToInventory(int $to_branch_id, int $product_id, int $quantity): bool
    {
        try {
            $existingToBranchProduct = BranchInventory::query()
                ->where('branch_id', $to_branch_id)
                ->where('product_id', $product_id)
                ->first();

            if ($existingToBranchProduct) {
                $existingToBranchProduct->increment('quantity', $quantity);
            } else {
                BranchInventory::create([
                    'branch_id'      => $to_branch_id,
                    'product_id'     => $product_id,
                    'quantity'       => $quantity,
                    'price'          => 0,
                    'critical_level' => 10,
                ]);
            }
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Reduce products from a branch's inventory.
     *
     * @param int $from_branch_id
     * @param int $product_id
     * @param int $quantity
     * @return bool
     */
    public function reduceProductsFromInventory(int $from_branch_id, int $product_id, int $quantity): bool
    {
        try {
            $fromBranchProduct = BranchInventory::query()
                ->where('branch_id', $from_branch_id)
                ->where('product_id', $product_id)
                ->first();

            if (!$fromBranchProduct) {
                return false;
            }

            $fromBranchProduct->decrement('quantity', $quantity);

            if ($fromBranchProduct->fresh()->quantity < 1) {
                $fromBranchProduct->delete();
            }

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Delete all inventory products for a branch.
     *
     * @param int $branch_id
     * @return bool
     */
    public function deleteAllInventoryProducts(int $branch_id): bool
    {
        return BranchInventory::where('branch_id', $branch_id)->delete() > 0;
    }

    /**
     * Delete specific products from all inventories.
     *
     * @param array|string $productIds
     * @return int
     */
    public function deleteSpecificProductsFromAllInventories(array|string $productIds): int
    {
        return BranchInventory::whereIn('product_id', (array) $productIds)->delete();
    }

    /**
     * Delete a specific inventory product from a branch.
     *
     * @param int $branch_id
     * @param int $product_id
     * @return bool
     */
    public function deleteSpecificInventoryProduct(int $branch_id, int $product_id): bool
    {
        return BranchInventory::where('branch_id', $branch_id)
                ->where('product_id', $product_id)
                ->delete() > 0;
    }

    /**
     * Check if a branch inventory exists.
     *
     * @param int $branch_id
     * @return bool
     */
    public function isInventoryExists(int $branch_id): bool
    {
        return BranchInventory::where('branch_id', $branch_id)->exists();
    }
}
