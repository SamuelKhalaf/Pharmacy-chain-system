<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface IOrder
{
    /**
     * Get all orders.
     *
     * @return Collection|null
     */
    public function getAllOrders(): ?Collection;

    /**
     * Find an order by its ID.
     *
     * @param int $id
     * @return object|null
     */
    public function findOrderById(int $id): ?object;

    /**
     * Create a new order.
     *
     * @param int $user_id
     * @param int $branchId
     * @param array $items
     * @return object|bool
     */
    public function createOrder(int $user_id, int $branchId, array $items): object|bool;

    /**
     * Cancel an order if it's still pending.
     *
     * @param int $id
     * @return bool
     */
    public function cancelOrder(int $id): bool;

    /**
     * Accept an order and reduce inventory stock.
     *
     * @param int $id
     * @return bool
     */
    public function acceptOrder(int $id): bool;

    /**
     * Get all items of a specific order.
     *
     * @param int $id
     * @return \Illuminate\Support\Collection
     */
    public function getOrderItems(int $id): \Illuminate\Support\Collection;
}
