<?php
namespace App\Repositories;

interface IOrder
{
    public function getAllOrders();
    public function findOrderById($id);
    public function createOrder($user_id, $branchId, $items);
    public function cancelOrder($id);
    public function acceptOrder($id);
}
