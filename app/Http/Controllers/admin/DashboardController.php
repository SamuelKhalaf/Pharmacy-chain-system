<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\IProductService;
use App\Services\ISaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected ISaleService $saleService;
    protected IProductService $productService;

    /**
     * @param ISaleService $saleService
     * @param IProductService $productService
     */
    public function __construct(ISaleService $saleService, IProductService $productService)
    {
        $this->saleService = $saleService;
        $this->productService = $productService;
    }

    /**
     * Display the dashboard view.
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }

    /**
     * Get sales count for a given month and year.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSalesCount(Request $request): JsonResponse
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $salesData = $this->saleService->getBranchSalesByMonth($month, $year);
        return response()->json($salesData);
    }

    /**
     * Get the top-selling products for a given year.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTopSellingProducts(Request $request): JsonResponse
    {
        $year = $request->input('year', now()->year);
        $topProducts = $this->productService->getTopSellingProducts($year);

        return response()->json($topProducts);
    }
}
