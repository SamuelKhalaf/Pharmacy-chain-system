<?php

namespace App\Services\implementation;

use App\Repositories\IOrder;
use App\Services\IOrderService;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrderService
 * Handles business logic related to orders.
 */
class OrderService implements IOrderService
{
    /**
     * @var IOrder
     */
    protected IOrder $orderRepository;

    /**
     * OrderService constructor.
     *
     * @param IOrder $orderRepository
     */
    public function __construct(IOrder $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Retrieve all orders.
     *
     * @return Collection
     */
    public function getOrders(): Collection
    {
        return $this->orderRepository->getAllOrders();
    }

    /**
     * Retrieve a specific order by its ID.
     *
     * @param int $id
     * @return object|null
     */
    public function getOrderById(int $id): ?object
    {
        return $this->orderRepository->findOrderById($id);
    }

    /**
     * Create a new order.
     *
     * @param int $user_id
     * @param array $request Contains 'branch_id' and 'items'
     * @return mixed
     */
    public function createOrder(int $user_id, array $request): mixed
    {
        return $this->orderRepository->createOrder($user_id, $request['branch_id'], $request['items']);
    }

    /**
     * Cancel an order by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function cancelOrder(int $id): bool
    {
        return $this->orderRepository->cancelOrder($id);
    }

    /**
     * Accept an order by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function acceptOrder(int $id): bool
    {
        return $this->orderRepository->acceptOrder($id);
    }
}
