<?php
namespace App\Services\implementation;

use App\Repositories\IBranchInventory;
use App\Repositories\ITransferRequest;
use App\Services\ITransferRequestService;
use Exception;
use Illuminate\Support\Facades\DB;

class TransferRequestService implements ITransferRequestService
{
    protected ITransferRequest $transferRequestRepository;
    protected IBranchInventory $branchInventoryRepository;

    public function __construct(ITransferRequest $transferRequestRepository,IBranchInventory $branchInventoryRepository)
    {
        $this->transferRequestRepository = $transferRequestRepository;
        $this->branchInventoryRepository = $branchInventoryRepository;
    }

    public function getAllTransferRequests()
    {
        return $this->transferRequestRepository->getAll();
    }

    public function getOneTransferRequest($id)
    {
        return $this->transferRequestRepository->findById($id);
    }

    public function createTransferRequest(array $data)
    {
        $branch_id = auth()->user()->branch_id;
        $transferRequests = [];

        foreach ($data['product_id'] as $index => $product_id) {
            $transferData = [
                'product_id' => $product_id,
                'quantity' => $data['quantity'][$index],
            ];

            if ($data['request_type'] === 'send') {
                $transferData['from_branch_id'] = $branch_id;
                $transferData['to_branch_id'] = $data['to_branch_id'];
            } elseif ($data['request_type'] === 'receive') {
                $transferData['to_branch_id'] = $branch_id;
                $transferData['from_branch_id'] = $data['from_branch_id'];
            }

            $transferRequests[] = $this->transferRequestRepository->create($transferData);
        }

        return $transferRequests;
    }

    public function updateTransferRequest(array $data,$id)
    {
        return $this->transferRequestRepository->update($data, $id);
    }

    public function deleteTransferRequest($id)
    {
        return $this->transferRequestRepository->delete($id);
    }

    public function getAllPendingRequests($status)
    {
        return $this->transferRequestRepository->getAllPendingRequests($status);
    }

    public function getOneByStatus($id,$status)
    {
        return $this->transferRequestRepository->getOneByStatus($id,$status);
    }

    public function getLatestTransferRequests()
    {
        return $this->transferRequestRepository->getLatestTransferRequests();
    }

    public function countPendingRequests()
    {
        return $this->transferRequestRepository->countPendingRequests();

    }

    public function transferRequestProducts(array $data)
    {
        try {
            DB::beginTransaction();

            $this->branchInventoryRepository
                ->addProductsToInventory($data['to_branch_id'],$data['product_id'],$data['quantity']);

            $this->branchInventoryRepository
                ->reduceProductsFromInventory($data['from_branch_id'],$data['product_id'],$data['quantity']);

            DB::commit();
            return true;
        }catch (Exception $exception){
            DB::rollBack();
            return false;
        }

    }
}
