<?php
namespace App\Repositories\implementation;

use App\Models\BranchInventory;
use App\Repositories\IBranchInventory;
use Illuminate\Support\Facades\DB;

class BranchInventoryRepository implements IBranchInventory
{
    public function getAllInventoriesByBranchID()
    {
        return BranchInventory::pluck('branch_id')->toArray();

    }
    public function getAllInventoryProducts($branch_id)
    {
        if ($this->isInventoryExists($branch_id)) {
            return DB::table('branch_inventory')
                ->join('products', 'branch_inventory.product_id', '=', 'products.id')
                ->where('branch_inventory.branch_id', $branch_id)
                ->select('branch_inventory.*', 'products.name as product_name' , 'products.id as product_id')
                ->get();
        }
        return false;
    }

    public function getSpecificInventoryProduct($branch_id, $product_id)
    {
        if ($this->isInventoryExists($branch_id)){
            return BranchInventory::query()
                ->where('branch_id',$branch_id)
                ->where('product_id',$product_id)
                ->first();
        }
        return false;
    }

    public function storeNewInventoryProducts(array $data)
    {
        $newInventoryRecords = [];

        foreach ($data['product_id'] as $index => $product_id) {

            $newInventoryRecord = BranchInventory::create([
                'branch_id'  => $data['branch_id'],
                'product_id' => $product_id,
                'quantity'   => $data['quantity'][$index],
                'price'      => $data['price'][$index],
            ]);
            $newInventoryRecords[] = $newInventoryRecord;
//            $existingInventoryProduct = BranchInventory::where('branch_id', $data['branch_id'])
//                ->where('product_id', $product_id)
//                ->first();
//
//            if ($existingInventoryProduct) {
//                $existingInventoryProduct->update([
//                    'quantity' => $existingInventoryProduct->quantity + $data['quantity'][$index],
//                    'price'    => $data['price'][$index],
//                ]);
//                $inventoryRecords[] = $existingInventoryProduct->refresh();
//            } else {
//
//            }
        }

        return $newInventoryRecords;
    }

    public function updateSpecificInventoryProduct(array $data, $branch_id,$product_id)
    {
        if ($this->isInventoryExists($branch_id)){
            return  BranchInventory::query()
                ->where('branch_id',$branch_id)
                ->where('product_id',$product_id)
                ->update($data);
        }
        return false;
   }

    public function deleteAllInventoryProducts($branch_id)
    {
        if ($this->isInventoryExists($branch_id)){
            return  BranchInventory::query()
                ->where('branch_id',$branch_id)
                ->delete();
        }
        return false;
    }
    public function deleteSpecificProductsFromAllInventories(array|string $productIds)
    {
        if (is_string($productIds)) {
            $productIds = [$productIds];
        }

        BranchInventory::whereIn('product_id', $productIds)->delete();
    }

    public function deleteSpecificInventoryProduct($branch_id, $product_id)
    {
        if ($this->isInventoryExists($branch_id)){
            return  BranchInventory::query()
                ->where('branch_id',$branch_id)
                ->where('product_id',$product_id)
                ->delete();
        }
        return false;
    }


    public function isInventoryExists($branch_id)
    {
        return BranchInventory::where('branch_id',$branch_id)->exists();
    }
}
