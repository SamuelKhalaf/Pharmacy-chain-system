<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\createInvoiceRequest;
use App\Services\IBranchInventoryService;
use App\Services\IProductService;
use App\Services\ISaleService;
use App\Services\IUserService;
use Illuminate\Http\Request;

class salesController extends Controller
{
    protected ISaleService $saleService;
    protected IBranchInventoryService $branchInventoryService;
    protected IUserService $userService;

    public function __construct(ISaleService $saleService , IBranchInventoryService $branchInventoryService , IUserService $userService)
    {
        $this->saleService = $saleService;
        $this->branchInventoryService = $branchInventoryService;
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authAdminBranchId = auth()->user()->branch_id;
        $invoices = $this->saleService->getSaleByBranchId($authAdminBranchId);
        return view('admin.invoices.index',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authAdminBranchId = auth()->user()->branch_id;
        $users = $this->userService->getAllUsers();
        $inventoryProducts = $this->branchInventoryService->getAllInventoryProducts($authAdminBranchId);
        return view('admin.invoices.create',compact(['inventoryProducts','users']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(createInvoiceRequest $request)
    {
        $data = $request->validated();
        $created = $this->saleService->createSaleWithItems($data);
        if (!$created){
            return redirect()->route('invoice.create')->with(['error' => 'An error occurred while create the invoice.']);
        }
        return redirect()->route('invoice.index')->with(['success' => 'Invoice created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoiceItems = $this->saleService->getSpecificSaleItems($id);
        return view('admin.invoices.view' , compact('invoiceItems'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->saleService->removeSale($id);
        if (!$deleted){
            return redirect()->route('invoice.index')->with(['error' => 'An error occurred while delete the invoice data.']);
        }
        return redirect()->route('invoice.index')->with(['success' => 'Invoice data deleted successfully.']);
    }
}
