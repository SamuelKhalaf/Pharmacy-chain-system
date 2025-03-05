<?php
namespace App\Services;

interface IOrderService
{
    public function getOrders();
    public function getOrderById($id);
    public function createOrder($user_id , $request);
    public function acceptOrder($id);
    public function cancelOrder($id);
}
