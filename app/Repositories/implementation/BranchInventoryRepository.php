<?php
namespace App\Repositories\implementation;

use App\Models\BranchInventory;
use App\Repositories\IBranchInventory;

class BranchInventoryRepository implements IBranchInventory
{
    public function getAllInventoryProducts($branch_id)
    {
        if ($this->isInventoryExists($branch_id)){
            return BranchInventory::where('branch_id',$branch_id)->get();
        }
        return false;
    }

    public function getSpecificInventoryProduct($branch_id, $product_id)
    {
        if ($this->isInventoryExists($branch_id)){
            return BranchInventory::query()
                ->where('branch_id',$branch_id)
                ->where('product_id',$product_id)
                ->get();
        }
        return false;
    }

    public function storeInventoryProducts(array $data)
    {
        $inventoryRecords = [];

        foreach ($data['product_id'] as $index => $product_id) {
            $existingInventoryProduct = BranchInventory::where('branch_id', $data['branch_id'])
                ->where('product_id', $product_id)
                ->first();

            if ($existingInventoryProduct) {
                $existingInventoryProduct->update([
                    'quantity' => $existingInventoryProduct->quantity + $data['quantity'][$index],
                    'price'    => $data['price'][$index],
                ]);
                $inventoryRecords[] = $existingInventoryProduct->refresh();
            } else {
                $newInventory = BranchInventory::create([
                    'branch_id'  => $data['branch_id'],
                    'product_id' => $product_id,
                    'quantity'   => $data['quantity'][$index],
                    'price'      => $data['price'][$index],
                ]);
                $inventoryRecords[] = $newInventory;
            }
        }

        return $inventoryRecords;
    }

    public function updateInventoryProducts(array $data, $branch_id)
    {
        // TODO: Implement updateInventoryProducts() method.
    }

    public function deleteAllInventoryProducts($branch_id)
    {
        // TODO: Implement deleteAllInventoryProducts() method.
    }

    public function deleteSpecificInventoryProduct($branch_id, $product_id)
    {
        // TODO: Implement deleteSpecificInventoryProduct() method.
    }


    public function isInventoryExists($branch_id)
    {
        return BranchInventory::where('branch_id',$branch_id)->exists();
    }
}
