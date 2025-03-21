<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateInvoiceRequest;
use App\Services\IBranchInventoryService;
use App\Services\ISaleService;
use App\Services\IUserService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class SalesController extends Controller
{
    protected ISaleService $saleService;
    protected IBranchInventoryService $branchInventoryService;
    protected IUserService $userService;

    /**
     * @param ISaleService $saleService
     * @param IBranchInventoryService $branchInventoryService
     * @param IUserService $userService
     */
    public function __construct(
        ISaleService $saleService,
        IBranchInventoryService $branchInventoryService,
        IUserService $userService
    ) {
        $this->saleService = $saleService;
        $this->branchInventoryService = $branchInventoryService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|View
     */
    public function index(): Factory|Application|View
    {
        $authAdminBranchId = auth()->user()->branch_id;
        $invoices = $this->saleService->getSaleByBranchId($authAdminBranchId);
        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|Application|View
     */
    public function create(): Factory|Application|View
    {
        $authAdminBranchId = auth()->user()->branch_id;
        $users = $this->userService->getAllUsers();
        $inventoryProducts = $this->branchInventoryService->getAllInventoryProducts($authAdminBranchId);
        return view('admin.invoices.create', compact(['inventoryProducts', 'users']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateInvoiceRequest $request
     * @return RedirectResponse
     */
    public function store(CreateInvoiceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $created = $this->saleService->createSaleWithItems($data);

        if (!$created) {
            return redirect()->route('invoice.create')->with(['error' => 'An error occurred while creating the invoice.']);
        }

        return redirect()->route('invoice.index')->with(['success' => 'Invoice created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return Factory|Application|View
     */
    public function show(string $id): Factory|Application|View
    {
        $invoiceItems = $this->saleService->getSpecificSaleItems($id);
        return view('admin.invoices.view', compact('invoiceItems'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $deleted = $this->saleService->removeSale($id);

        if (!$deleted) {
            return redirect()->route('invoice.index')->with(['error' => 'An error occurred while deleting the invoice data.']);
        }

        return redirect()->route('invoice.index')->with(['success' => 'Invoice data deleted successfully.']);
    }
}
