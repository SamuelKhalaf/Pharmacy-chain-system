<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Services\IOrderService;
use Illuminate\Http\JsonResponse;

/**
 * Class OrderController
 *
 * Handles order-related API endpoints.
 */
class OrderController extends Controller
{
    /**
     * @var IOrderService
     */
    protected IOrderService $orderService;

    /**
     * OrderController constructor.
     *
     * @param IOrderService $orderService
     */
    public function __construct(IOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Retrieve all orders.
     *
     * @return JsonResponse
     */
    public function allOrders(): JsonResponse
    {
        $orders = $this->orderService->getOrders();
        return response()->json($orders);
    }

    /**
     * Store a new order.
     *
     * @param CreateOrderRequest $request
     * @return JsonResponse
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user_id = auth()->id();
        $order = $this->orderService->createOrder($user_id, $data);

        if ($order) {
            return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
        }

        return response()->json(['message' => 'An error occurred'], 400);
    }

    /**
     * Retrieve a specific order by ID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $order = $this->orderService->getOrderById($id);

        if ($order) {
            return response()->json(['message' => 'Order exists', 'order' => $order]);
        }

        return response()->json(['message' => 'An error occurred'], 400);
    }

    /**
     * Accept an order.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function acceptOrder(string $id): JsonResponse
    {
        $accepted = $this->orderService->acceptOrder($id);

        if ($accepted) {
            return response()->json(['message' => 'Order accepted successfully']);
        }

        return response()->json(['message' => 'An error occurred'], 400);
    }

    /**
     * Cancel an order.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function cancelOrder(string $id): JsonResponse
    {
        $cancelled = $this->orderService->cancelOrder($id);

        if ($cancelled) {
            return response()->json(['message' => 'Order cancelled successfully']);
        }

        return response()->json(['message' => 'An error occurred'], 400);
    }
}
