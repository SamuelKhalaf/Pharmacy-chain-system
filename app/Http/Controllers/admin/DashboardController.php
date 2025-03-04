<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\IProductService;
use App\Services\ISaleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected ISaleService $saleService;
    protected IProductService $productService;

    public function __construct(ISaleService $saleService , IProductService $productService)
    {
        $this->saleService = $saleService;
        $this->productService = $productService;
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function getSalesCount(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $salesData = $this->saleService->getBranchSalesByMonth($month, $year);
        return response()->json($salesData);
    }

    public function getTopSellingProducts(Request $request)
    {
        $year = $request->input('year', now()->year);
        $topProducts = $this->productService->getTopSellingProducts($year);

        return response()->json($topProducts);
    }
}
