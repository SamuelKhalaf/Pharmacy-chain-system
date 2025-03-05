<?php

namespace App\Services\implementation;

use App\Repositories\IOrder;
use App\Services\IOrderService;
use Illuminate\Support\Facades\Auth;

class OrderService implements IOrderService
{
    protected IOrder $orderRepository;

    public function __construct(IOrder $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getOrders()
    {
        return $this->orderRepository->getAllOrders();
    }

    public function getOrderById($id)
    {
        return $this->orderRepository->findOrderById($id);
    }

    public function createOrder($user_id , $request)
    {
        return $this->orderRepository->createOrder($user_id, $request['branch_id'], $request['items']);
    }

    public function cancelOrder($id)
    {
        return $this->orderRepository->cancelOrder($id);
    }

    public function acceptOrder($id)
    {
        return $this->orderRepository->acceptOrder($id);
    }
}
