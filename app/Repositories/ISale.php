<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ISale
{
    /**
     * Get all sales with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Find sales by branch ID with optional date range.
     *
     * @param int $branch_id
     * @param string|null $start_date
     * @param string|null $end_date
     * @return Collection
     */
    public function findByBranchId(int $branch_id, ?string $start_date = null, ?string $end_date = null): Collection;

    /**
     * Get specific sale items for a given sale.
     *
     * @param int $sale_id
     * @return Collection
     */
    public function getSpecificSaleItems(int $sale_id): Collection;

    /**
     * Create a new sale transaction.
     *
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool;

    /**
     * Update an existing sale.
     *
     * @param array $data
     * @param int $id
     * @return void
     */
    public function update(array $data, int $id): void;

    /**
     * Delete a sale by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Get the count of sales for each branch in a given month and year.
     *
     * @param int $month
     * @param int $year
     * @return \Illuminate\Support\Collection
     */
    public function getBranchSalesCountByMonth(int $month, int $year): \Illuminate\Support\Collection;

    /**
     * Get the total quantity of a sold product in a given branch with optional date range.
     *
     * @param int $branch_id
     * @param int $product_id
     * @param string|null $start_date
     * @param string|null $end_date
     * @return \Illuminate\Support\Collection
     */
    public function getSoldProductQuantity(int $branch_id, int $product_id, ?string $start_date = null, ?string $end_date = null): \Illuminate\Support\Collection;

    /**
     * Check if a sale exists by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function isExists(int $id): bool;
}
