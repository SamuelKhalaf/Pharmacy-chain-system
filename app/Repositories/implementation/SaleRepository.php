<?php
namespace App\Repositories\implementation;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Repositories\IBranchInventory;
use App\Repositories\ISale;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class SaleRepository implements ISale
{
    protected IBranchInventory $branchInventoryRepository;

    public function __construct(IBranchInventory $branchInventoryRepository)
    {
        $this->branchInventoryRepository = $branchInventoryRepository;
    }
    public function getAll()
    {
        return Sale::query()
            ->join('users','sales.user_id' ,'=','users.id')
            ->join('branches', 'sales.branch_id', '=', 'branches.id')
            ->select([
                'sales.id',
                'sales.total_price',
                'sales.created_at',
                'users.name as customer_name',
                'branches.name as branch_name'
            ])
            ->get();
    }

    public function findByBranchId($branch_id)
    {
        return Sale::query()
            ->join('users','sales.user_id' ,'=','users.id')
            ->join('branches', 'sales.branch_id', '=', 'branches.id')
            ->select([
                'sales.id',
                'sales.total_price',
                'sales.created_at',
                'users.name as customer_name',
                'branches.name as branch_name'
            ])
            ->where('sales.branch_id',$branch_id)
            ->get();
    }

    public function getSpecificSaleItems($sale_id)
    {
        return Sale::query()
            ->join('sale_items','sales.id' ,'=','sale_items.sale_id')
            ->join('products','sale_items.product_id' ,'=','products.id')
            ->select([
                'sale_items.quantity',
                'sale_items.price',
                'products.name as product_name'
            ])
            ->where('sales.id',$sale_id)
            ->get();
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            // Create the sale
            $sale = Sale::create([
                'branch_id' => $data['branch_id'],
                'user_id' => $data['user_id'],
                'total_price' => $data['total_price'],
            ]);

            // Add sale items
            foreach ($data['product_id'] as $index => $product_id) {
                $inventoryProduct = $this->branchInventoryRepository->getSpecificInventoryProduct($data['branch_id'],$product_id);
                // reduce the product quantity
                $this->branchInventoryRepository->reduceProductsFromInventory($data['branch_id'],$product_id,$data['quantity'][$index]);
                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product_id,
                    'quantity'   => $data['quantity'][$index],
                    'price'      => $inventoryProduct->price,
                ]);
            }
            DB::commit();
            return true;
        }catch (Exception $exception){
            DB::rollBack();
            return false;
        }
    }

    public function update(array $data, $id){}


    public function delete($id)
    {
        if ($this->isExists($id)){
            try {
                DB::beginTransaction();

                SaleItem::where('sale_id', $id)->delete();
                Sale::where('id', $id)->delete();

                DB::commit();
                return true;
            }catch (Exception $exception){
                DB::rollBack();
                return false;
            }
        }else{
            return false;
        }
    }

    public function isExists($id)
    {
        return Sale::where('id',$id)->exists();
    }
}
