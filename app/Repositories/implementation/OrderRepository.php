<?php
namespace App\Repositories\implementation;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\IBranchInventory;
use App\Repositories\IOrder;
use App\Repositories\IProduct;
use Illuminate\Support\Facades\DB;

class OrderRepository implements IOrder
{
    protected IProduct $productRepository;
    protected IBranchInventory $branchInventoryRepository;

    public function __construct(IProduct $productRepository , IBranchInventory $branchInventoryRepository)
    {
        $this->branchInventoryRepository = $branchInventoryRepository;
        $this->productRepository = $productRepository;
    }
    public function getAllOrders()
    {
        $orders = DB::table('orders')
            ->join('branches', 'orders.branch_id', '=', 'branches.id')
            ->select([
                'orders.id',
                'orders.total_price',
                'orders.status',
                'orders.branch_id',
                'branches.name as branch_name'
            ])
            ->get();

        if ($orders->isEmpty()) {
            return null;
        }
        foreach ($orders as $order) {
            $order->orderItems = $this->getOrderItems($order->id);
        }
        return $orders;
    }

    public function findOrderById($id)
    {
        $orderData = DB::table('orders')
            ->where('orders.id', '=', $id)
            ->join('branches', 'orders.branch_id', '=', 'branches.id')
            ->select([
                'orders.id',
                'orders.total_price',
                'orders.status',
                'orders.branch_id',
                'branches.name as branch_name'
            ])
            ->first();

        if (!$orderData) {
            return null;
        }

        $orderData->orderItems = $this->getOrderItems($id);

        return $orderData;
    }


    public function createOrder($user_id, $branchId, $items)
    {
        try {
            DB::beginTransaction();
            $order = Order::create([
                'user_id' => $user_id,
                'branch_id' => $branchId,
                'total_price' => 0,
                'status' => 'pending',
            ]);

            $totalPrice = 0;
            foreach ($items as $item) {

                $product = $this->branchInventoryRepository->getSpecificInventoryProduct($branchId,$item['product_id']);
                if ($product->quantity < $item['quantity']) {
                    return false;
                }
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->product_id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                $totalPrice += $product->price * $item['quantity'];
            }
            $order->update(['total_price' => $totalPrice]);
            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function acceptOrder($id)
    {
        try {
            DB::beginTransaction();
            $order = $this->findOrderById($id);
            if (!$order || $order->status !== 'pending') {
                return false;
            }
            $branchId = $order->branch_id;
            foreach ($order->order_items as $item) {
                $product = $this->branchInventoryRepository->getSpecificInventoryProduct($branchId, $item->product_id);
                if (!$product) {
                    DB::rollBack();
                    return false;
                }
                $this->branchInventoryRepository->reduceProductsFromInventory($branchId,$item->product_id,$item->quantity);
            }
            DB::table('orders')
                ->where('id', $id)
                ->update(['status' => 'accepted']);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function cancelOrder($id)
    {
        $order = $this->findOrderById($id);
        if (!$order || $order->status !== 'pending') {
            return false;
        }
        return DB::table('orders')
            ->where('id', $id)
            ->update(['status' => 'canceled']);
    }

    public function getOrderItems($id)
    {
        return DB::table('order_items')
            ->where('order_items.order_id', '=', $id)
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select([
                'order_items.id as order_item_id',
                'order_items.quantity',
                'order_items.price as one_piece_price',
                'order_items.product_id',
                'products.name as product_name'
            ])
            ->get();

    }
}
