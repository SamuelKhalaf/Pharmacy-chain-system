<?php
namespace App\Services\implementation;

use App\Repositories\IBranchInventory;
use App\Repositories\ISale;
use App\Services\ISaleService;

class SaleService implements ISaleService
{
    protected ISale $saleRepository;
    protected IBranchInventory $branchInventoryRepository;

    public function __construct(ISale $saleRepository ,IBranchInventory $branchInventoryRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->branchInventoryRepository = $branchInventoryRepository;
    }

    public function getSales()
    {
        return $this->saleRepository->getAll();
    }

    public function getSaleByBranchId($branch_id)
    {
        return $this->saleRepository->findByBranchId($branch_id);
    }

    public function getSpecificSaleItems($sale_id)
    {
        return $this->saleRepository->getSpecificSaleItems($sale_id);
    }

    public function createSaleWithItems(array $data)
    {
        $data['branch_id'] = auth()->user()->branch_id;
        $data['total_price'] = $this->calculateInvoiceTotalPrice($data);
        return $this->saleRepository->create($data);
    }

    public function updateSaleWithItems(array $data, $id)
    {
        return $this->saleRepository->update($data, $id);
    }

    public function removeSale($id)
    {
        return $this->saleRepository->delete($id);
    }

    private function calculateInvoiceTotalPrice(array $data)
    {
        $totalPrice = 0;
        foreach ($data['product_id'] as $index => $product_id){
            $inventoryProduct = $this->branchInventoryRepository->getSpecificInventoryProduct($data['branch_id'],$product_id);
            $quantityPrice = $data['quantity'][$index] * $inventoryProduct->price;
            $totalPrice += $quantityPrice;
        }
        return $totalPrice;
    }

    public function isSaleExists($id)
    {
        return $this->saleRepository->isExists($id);
    }
}
