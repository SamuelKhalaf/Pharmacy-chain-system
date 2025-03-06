<?php

namespace App\Services\implementation;

use App\Repositories\IBranchInventory;
use App\Repositories\ITransferRequest;
use App\Services\ITransferRequestService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class TransferRequestService
 *
 * Handles transfer requests between branches.
 */
class TransferRequestService implements ITransferRequestService
{
    /**
     * @var ITransferRequest
     */
    protected ITransferRequest $transferRequestRepository;

    /**
     * @var IBranchInventory
     */
    protected IBranchInventory $branchInventoryRepository;

    /**
     * TransferRequestService constructor.
     *
     * @param ITransferRequest $transferRequestRepository
     * @param IBranchInventory $branchInventoryRepository
     */
    public function __construct(ITransferRequest $transferRequestRepository, IBranchInventory $branchInventoryRepository)
    {
        $this->transferRequestRepository = $transferRequestRepository;
        $this->branchInventoryRepository = $branchInventoryRepository;
    }

    /**
     * Retrieve all transfer requests.
     *
     * @return LengthAwarePaginator
     */
    public function getAllTransferRequests(): LengthAwarePaginator
    {
        return $this->transferRequestRepository->getAll();
    }

    /**
     * Retrieve a specific transfer request by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getOneTransferRequest(int $id): mixed
    {
        return $this->transferRequestRepository->findById($id);
    }

    /**
     * Create a new transfer request.
     *
     * @param array $data
     * @return array
     */
    public function createTransferRequest(array $data): array
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

    /**
     * Update an existing transfer request.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateTransferRequest(array $data, int $id): bool
    {
        return $this->transferRequestRepository->update($data, $id);
    }

    /**
     * Delete a transfer request by ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTransferRequest(int $id): bool
    {
        return $this->transferRequestRepository->delete($id);
    }

    /**
     * Retrieve all pending transfer requests by status.
     *
     * @param string $status
     * @return Collection
     */
    public function getAllPendingRequests(string $status): Collection
    {
        return $this->transferRequestRepository->getAllPendingRequests($status);
    }

    /**
     * Retrieve a single transfer request by ID and status.
     *
     * @param int $id
     * @param string $status
     * @return object|null
     */
    public function getOneByStatus(int $id, string $status): ?object
    {
        return $this->transferRequestRepository->getOneByStatus($id, $status);
    }

    /**
     * Retrieve the latest transfer requests.
     *
     * @return Collection
     */
    public function getLatestTransferRequests(): Collection
    {
        return $this->transferRequestRepository->getLatestTransferRequests();
    }

    /**
     * Count pending transfer requests.
     *
     * @return int
     */
    public function countPendingRequests(): int
    {
        return $this->transferRequestRepository->countPendingRequests();
    }

    /**
     * Transfer products between branches.
     *
     * @param array $data
     * @return bool
     */
    public function transferRequestProducts(array $data): bool
    {
        try {
            DB::beginTransaction();

            $this->branchInventoryRepository
                ->addProductsToInventory($data['to_branch_id'], $data['product_id'], $data['quantity']);

            $this->branchInventoryRepository
                ->reduceProductsFromInventory($data['from_branch_id'], $data['product_id'], $data['quantity']);

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            return false;
        }
    }
}
