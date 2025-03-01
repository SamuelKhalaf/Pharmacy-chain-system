<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetBranchInventoryRequest;
use App\Http\Requests\RequsetProductsRequest;
use App\Repositories\IBranch;
use App\Repositories\IBranchInventory;
use App\Services\IBranchInventoryService;
use App\Services\ITransferRequestService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TransferRequestController extends Controller
{
    protected IBranch $branchRepository;
    protected IBranchInventoryService $branchInventoryService;
    protected ITransferRequestService $transferRequestService;
    protected IBranchInventory $branchInventoryRepository;

    public function __construct(IBranch $branchRepository,IBranchInventoryService $branchInventoryService,
                                ITransferRequestService $transferRequestService , IBranchInventory $branchInventoryRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->branchInventoryService = $branchInventoryService;
        $this->transferRequestService = $transferRequestService;
        $this->branchInventoryRepository = $branchInventoryRepository;
    }

    public function index()
    {
        $requests = $this->transferRequestService->getAllPendingRequests('pending');
        return view('admin.request.index',compact(['requests']));
    }
    public function requestDropdown()
    {
        $requests = $this->transferRequestService->getLatestTransferRequests();
        return response()->json($requests);
    }

    public function countPendingRequests()
    {
        $count = $this->transferRequestService->countPendingRequests();
        return response()->json(['count' => $count]);
    }

    public function showRequestForm()
    {
        $authAdminBranchId = auth()->user()->branch_id;
        $allOtherBranches = $this->branchRepository->getWhereNotIn($authAdminBranchId);
        $allOtherOldBranches = $this->branchRepository->getOtherOldBranches();
        return view('admin.request.form',compact(['allOtherBranches','allOtherOldBranches']));
    }

    public function getSpecificBranchProducts(GetBranchInventoryRequest $request)
    {
        $data = $request->validated();
        $inventory = $this->branchInventoryService->getAllInventoryProducts($data['branch_id']);
        return response()->json(['data' => $inventory]);
    }

    public function storeRequest(RequsetProductsRequest $request)
    {
        $data = $request->validated();

        $stored = $this->transferRequestService->createTransferRequest($data);
        if (!$stored){
            return redirect()->route('request.show-request-form')->with(['error' => 'An error occurred while store the request data.']);
        }
        return redirect()->route('request.show-request-form')->with(['success' => 'Request data stored successfully.']);
    }

    public function acceptRequest($transferRequestId)
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

    public function cancelRequest($transferRequestId): JsonResponse
    {
        return $this->updateRequestStatusAndRespond($transferRequestId, 'cancelled', 'Request cancelled successfully', 'cancel');
    }

    private function updateRequestStatusAndRespond($transferRequestId, $status, $message, $responseKey): JsonResponse
    {
        $request = $this->transferRequestService->getOneByStatus($transferRequestId, 'pending');
        if (!$request) {
            return $this->jsonResponse('reject', 'Request not found or already processed');
        }

        $this->transferRequestService->updateTransferRequest(['status' => $status], $transferRequestId);
        return $this->jsonResponse($responseKey, $message);
    }

    private function jsonResponse($key, $message, $statusCode = 200): JsonResponse
    {
        return response()->json([$key => true, 'message' => $message], $statusCode);
    }

}
