<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Services\IOrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected IOrderService $orderService;

    public function __construct(IOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function allOrders()
    {
        $orders = $this->orderService->getOrders();
        return response()->json($orders);
    }

    public function store(CreateOrderRequest $request)
    {
        $data = $request->validated();
        if (auth()->check()) {
            $user_id = auth()->id();
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $order = $this->orderService->createOrder($user_id , $data);
        if ($order){
            return response()->json(['message' => 'Order Created successfully', 'order' => $order], 201);
        }
        return response()->json(['message' => 'An error occurred'], 400);
    }

    public function show(string $id)
    {
        $order = $this->orderService->getOrderById($id);
        if ($order){
            return response()->json(['message' => 'Order is exists' , 'order' => $order]);
        }
        return response()->json(['message' => 'An error occurred'], 400);
    }

    public function acceptOrder(string $id)
    {
        $accepted = $this->orderService->acceptOrder($id);
        if ($accepted){
            return response()->json(['message' => 'Order Accepted successfully']);
        }
        return response()->json(['message' => 'An error occurred'], 400);
    }

    public function cancelOrder(string $id)
    {
        $cancelled = $this->orderService->cancelOrder($id);
        if ($cancelled){
            return response()->json(['message' => 'Order Cancelled successfully']);
        }
        return response()->json(['message' => 'An error occurred'], 400);
    }}
