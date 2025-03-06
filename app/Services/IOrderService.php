<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

/**
 * Interface IOrderService
 * Defines the contract for order-related operations.
 */
interface IOrderService
{
    /**
     * Retrieve all orders.
     *
     * @return Collection
     */
    public function getOrders(): Collection;

    /**
     * Retrieve a specific order by its ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getOrderById(int $id): mixed;

    /**
     * Create a new order.
     *
     * @param int $user_id
     * @param array $request Contains 'branch_id' and 'items'
     * @return mixed
     */
    public function createOrder(int $user_id, array $request): mixed;

    /**
     * Accept an order by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function acceptOrder(int $id): bool;

    /**
     * Cancel an order by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function cancelOrder(int $id): bool;
}
