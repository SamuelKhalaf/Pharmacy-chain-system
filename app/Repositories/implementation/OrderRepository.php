<?php
namespace App\Repositories\implementation;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\IBranchInventory;
use App\Repositories\IOrder;
use App\Repositories\IProduct;
use Illuminate\Database\Eloquent\Collection;
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

    /**
     * Get all orders with their branch names and items.
     *
     * @return Collection|null
     */
    public function getAllOrders(): ?Collection
    {
        $orders = DB::table('orders')
            ->join('branches', 'orders.branch_id', '=', 'branches.id')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->select([
                'orders.id',
                'orders.total_price',
                'orders.status',
                'orders.branch_id',
                'branches.name as branch_name',
                DB::raw('JSON_ARRAYAGG(JSON_OBJECT(
                    "order_item_id", order_items.id,
                    "quantity", order_items.quantity,
                    "one_piece_price", order_items.price,
                    "product_id", order_items.product_id,
                    "product_name", products.name
                )) as order_items')
            ])
            ->groupBy('orders.id')
            ->get();

        return $orders->isEmpty() ? null : $orders;
    }

    /**
     * Find an order by its ID.
     *
     * @param int $id
     * @return object|null
     */
    public function findOrderById(int $id): ?object
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

    /**
     * Create a new order.
     *
     * @param int $user_id
     * @param int $branchId
     * @param array $items
     * @return object|bool
     */
    public function createOrder(int $user_id, int $branchId, array $items): object|bool
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

    /**
     * Accept an order and reduce product quantities from inventory.
     *
     * @param int $id
     * @return bool
     */
    public function acceptOrder(int $id): bool
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

    /**
     * Cancel an order if it's still pending.
     *
     * @param int $id
     * @return bool
     */
    public function cancelOrder(int $id): bool
    {
        $order = $this->findOrderById($id);
        if (!$order || $order->status !== 'pending') {
            return false;
        }
        return DB::table('orders')
            ->where('id', $id)
            ->update(['status' => 'canceled']);
    }

    /**
     * Get all items of a specific order.
     *
     * @param int $id
     * @return \Illuminate\Support\Collection
     */
    public function getOrderItems(int $id): \Illuminate\Support\Collection
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
