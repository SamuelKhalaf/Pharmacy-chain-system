<?php

namespace App\Services\implementation;

use App\Repositories\IBranch;
use App\Repositories\IBranchInventory;
use App\Repositories\ISale;
use App\Services\ISaleService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Service responsible for handling sales-related operations.
 */
class SaleService implements ISaleService
{
    protected ISale $saleRepository;
    protected IBranchInventory $branchInventoryRepository;
    protected IBranch $branchRepository;

    /**
     * SaleService constructor.
     *
     * @param ISale $saleRepository
     * @param IBranchInventory $branchInventoryRepository
     * @param IBranch $branchRepository
     */
    public function __construct(
        ISale $saleRepository,
        IBranchInventory $branchInventoryRepository,
        IBranch $branchRepository
    ) {
        $this->saleRepository = $saleRepository;
        $this->branchInventoryRepository = $branchInventoryRepository;
        $this->branchRepository = $branchRepository;
    }

    /**
     * Retrieve all sales.
     *
     * @return LengthAwarePaginator
     */
    public function getSales(): LengthAwarePaginator
    {
        return $this->saleRepository->getAll();
    }

    /**
     * Retrieve sales for a specific branch within an optional date range.
     *
     * @param int $branch_id
     * @param string|null $start_date
     * @param string|null $end_date
     * @return Collection
     */
    public function getSaleByBranchId(int $branch_id, ?string $start_date = null, ?string $end_date = null): Collection
    {
        return $this->saleRepository->findByBranchId($branch_id, $start_date, $end_date);
    }

    /**
     * Retrieve items for a specific sale.
     *
     * @param int $sale_id
     * @return Collection
     */
    public function getSpecificSaleItems(int $sale_id): Collection
    {
        return $this->saleRepository->getSpecificSaleItems($sale_id);
    }

    /**
     * Create a new sale with its items.
     *
     * @param array $data
     * @return bool
     */
    public function createSaleWithItems(array $data): bool
    {
        $data['branch_id'] = auth()->user()->branch_id;
        $data['total_price'] = $this->calculateInvoiceTotalPrice($data);
        return $this->saleRepository->create($data);
    }

    /**
     * Update an existing sale with new data.
     *
     * @param array $data
     * @param int $id
     * @return void
     */
    public function updateSaleWithItems(array $data, int $id): void
    {
        $this->saleRepository->update($data, $id);
    }

    /**
     * Remove a sale by ID.
     *
     * @param int $id
     * @return bool
     */
    public function removeSale(int $id): bool
    {
        return $this->saleRepository->delete($id);
    }

    /**
     * Calculate the total price of a sale invoice.
     *
     * @param array $data
     * @return float
     */
    private function calculateInvoiceTotalPrice(array $data): float
    {
        $totalPrice = 0;
        foreach ($data['product_id'] as $index => $product_id) {
            $inventoryProduct = $this->branchInventoryRepository->getSpecificInventoryProduct($data['branch_id'], $product_id);
            $quantityPrice = $data['quantity'][$index] * $inventoryProduct->price;
            $totalPrice += $quantityPrice;
        }
        return $totalPrice;
    }

    /**
     * Get sales data for all branches within a given month and year.
     *
     * @param int $month
     * @param int $year
     * @return array
     */
    public function getBranchSalesByMonth(int $month, int $year): array
    {
        $salesData = $this->saleRepository->getBranchSalesCountByMonth($month, $year);
        $formattedData = [];

        foreach ($salesData as $sale) {
            $branch = $this->branchRepository->findById($sale->branch_id);
            $formattedData[] = [
                'branch' => $branch->name,
                'sales' => $sale->count,
            ];
        }

        return $formattedData;
    }

    /**
     * Get the quantity of a sold product within a branch and optional date range.
     *
     * @param int $branchId
     * @param int $productId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return Collection
     */
    public function getSoldProductQuantity(int $branchId, int $productId, ?string $startDate = null, ?string $endDate = null): Collection
    {
        return $this->saleRepository->getSoldProductQuantity($branchId, $productId, $startDate, $endDate);
    }
}
