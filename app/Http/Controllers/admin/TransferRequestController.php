<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetBranchInventoryRequest;
use App\Http\Requests\RequsetProductsRequest;
use App\Services\IBranchInventoryService;
use App\Services\IBranchService;
use App\Services\ITransferRequestService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class TransferRequestController extends Controller
{
    protected IBranchService $branchService;
    protected IBranchInventoryService $branchInventoryService;
    protected ITransferRequestService $transferRequestService;

    /**
     * @param IBranchService $branchService
     * @param IBranchInventoryService $branchInventoryService
     * @param ITransferRequestService $transferRequestService
     */
    public function __construct(
        IBranchService $branchService,
        IBranchInventoryService $branchInventoryService,
        ITransferRequestService $transferRequestService
    ) {
        $this->branchService = $branchService;
        $this->branchInventoryService = $branchInventoryService;
        $this->transferRequestService = $transferRequestService;
    }

    /**
     * Display a list of all pending transfer requests.
     *
     * @return Factory|Application|View
     */
    public function index(): Factory|Application|View
    {
        $requests = $this->transferRequestService->getAllPendingRequests('pending');
        return view('admin.request.index', compact(['requests']));
    }

    /**
     * Get the latest transfer requests for a dropdown.
     *
     * @return JsonResponse
     */
    public function requestDropdown(): JsonResponse
    {
        $requests = $this->transferRequestService->getLatestTransferRequests();
        return response()->json($requests);
    }

    /**
     * Count the number of pending transfer requests.
     *
     * @return JsonResponse
     */
    public function countPendingRequests(): JsonResponse
    {
        $count = $this->transferRequestService->countPendingRequests();
        return response()->json(['count' => $count]);
    }

    /**
     * Show the form to create a transfer request.
     *
     * @return Factory|Application|View
     */
    public function showRequestForm(): Factory|Application|View
    {
        $authAdminBranchId = auth()->user()->branch_id;
        $allOtherBranches = $this->branchService->getWhereNotIn($authAdminBranchId);
        $allOtherOldBranches = $this->branchService->getOtherOldBranches();
        return view('admin.request.form', compact(['allOtherBranches', 'allOtherOldBranches']));
    }

    /**
     * Get products of a specific branch for AJAX request.
     *
     * @param GetBranchInventoryRequest $request
     * @return JsonResponse
     */
    public function getSpecificBranchProducts(GetBranchInventoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $inventory = $this->branchInventoryService->getAllInventoryProducts($data['branch_id']);
        return response()->json(['data' => $inventory]);
    }

    /**
     * Store a new transfer request.
     *
     * @param RequsetProductsRequest $request
     * @return RedirectResponse
     */
    public function storeRequest(RequsetProductsRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $stored = $this->transferRequestService->createTransferRequest($data);

        if (!$stored) {
            return redirect()->route('request.show-request-form')->with(['error' => 'An error occurred while storing the request data.']);
        }

        return redirect()->route('request.show-request-form')->with(['success' => 'Request data stored successfully.']);
    }

    /**
     * Accept a transfer request and process the transfer.
     *
     * @param int $transferRequestId
     * @return JsonResponse
     */
    public function acceptRequest(int $transferRequestId): JsonResponse
    {
        $request = $this->transferRequestService->getOneByStatus($transferRequestId, 'pending');

        if (!$request) {
            return $this->jsonResponse('reject', 'Request not found or already processed');
        }

        $branchInventoryProduct = $this->branchInventoryService
            ->getOneInventoryProduct($request->from_branch_id, $request->product_id);

        if (!$branchInventoryProduct) {
            return $this->updateRequestStatusAndRespond($transferRequestId, 'rejected', 'Product not found in branch inventory', 'reject');
        }

        if ($request->quantity <= $branchInventoryProduct->quantity) {
            $transferData = [
                'from_branch_id' => $request->from_branch_id,
                'to_branch_id'   => $request->to_branch_id,
                'product_id'     => $request->product_id,
                'quantity'       => $request->quantity,
            ];

            if ($this->transferRequestService->transferRequestProducts($transferData)) {
                return $this->updateRequestStatusAndRespond($transferRequestId, 'completed', 'Transfer request accepted successfully', 'success');
            }
        }

        return $this->updateRequestStatusAndRespond($transferRequestId, 'rejected', 'Insufficient quantity in branch inventory', 'reject');
    }

    /**
     * Cancel a transfer request.
     *
     * @param int $transferRequestId
     * @return JsonResponse
     */
    public function cancelRequest(int $transferRequestId): JsonResponse
    {
        return $this->updateRequestStatusAndRespond($transferRequestId, 'cancelled', 'Request cancelled successfully', 'cancel');
    }

    /**
     * Update request status and return JSON response.
     *
     * @param int $transferRequestId
     * @param string $status
     * @param string $message
     * @param string $responseKey
     * @return JsonResponse
     */
    private function updateRequestStatusAndRespond(
        int $transferRequestId,
        string $status,
        string $message,
        string $responseKey
    ): JsonResponse {
        $request = $this->transferRequestService->getOneByStatus($transferRequestId, 'pending');

        if (!$request) {
            return $this->jsonResponse('reject', 'Request not found or already processed');
        }

        $this->transferRequestService->updateTransferRequest(['status' => $status], $transferRequestId);
        return $this->jsonResponse($responseKey, $message);
    }

    /**
     * Return a JSON response.
     *
     * @param string $key
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    private function jsonResponse(string $key, string $message, int $statusCode = 200): JsonResponse
    {
        return response()->json([$key => true, 'message' => $message], $statusCode);
    }
}
