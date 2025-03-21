<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface ISaleService
 *
 * Defines the contract for managing sales operations.
 */
interface ISaleService
{
    /**
     * Retrieve all sales.
     *
     * @return LengthAwarePaginator
     */
    public function getSales(): LengthAwarePaginator;

    /**
     * Retrieve sales for a specific branch within an optional date range.
     *
     * @param int $branch_id
     * @param string|null $start_date
     * @param string|null $end_date
     * @return Collection
     */
    public function getSaleByBranchId(int $branch_id, ?string $start_date = null, ?string $end_date = null): Collection;

    /**
     * Retrieve specific sale items.
     *
     * @param int $sale_id
     * @return Collection
     */
    public function getSpecificSaleItems(int $sale_id): Collection;

    /**
     * Create a new sale with its items.
     *
     * @param array $data
     * @return mixed
     */
    public function createSaleWithItems(array $data): mixed;

    /**
     * Update an existing sale with new data.
     *
     * @param array $data
     * @param int $id
     * @return void
     */
    public function updateSaleWithItems(array $data, int $id): void;

    /**
     * Remove a sale by ID.
     *
     * @param int $id
     * @return bool
     */
    public function removeSale(int $id): bool;

    /**
     * Get sales data for all branches within a given month and year.
     *
     * @param int $month
     * @param int $year
     * @return array
     */
    public function getBranchSalesByMonth(int $month, int $year): array;

    /**
     * Get the quantity of a sold product within a branch and optional date range.
     *
     * @param int $branchId
     * @param int $productId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return Collection
     */
    public function getSoldProductQuantity(int $branchId, int $productId, ?string $startDate = null, ?string $endDate = null): Collection;
}
