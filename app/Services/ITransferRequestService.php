<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface ITransferRequestService
 *
 * Defines the contract for managing transfer requests.
 */
interface ITransferRequestService
{
    /**
     * Retrieve all transfer requests.
     *
     * @return LengthAwarePaginator
     */
    public function getAllTransferRequests(): LengthAwarePaginator;

    /**
     * Retrieve a specific transfer request by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getOneTransferRequest(int $id): mixed;

    /**
     * Create a new transfer request.
     *
     * @param array $data
     * @return array
     */
    public function createTransferRequest(array $data): array;

    /**
     * Update an existing transfer request.
     *
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function updateTransferRequest(array $data, int $id): mixed;

    /**
     * Delete a transfer request by ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTransferRequest(int $id): bool;

    /**
     * Retrieve all pending transfer requests by status.
     *
     * @param string $status
     * @return Collection
     */
    public function getAllPendingRequests(string $status): Collection;

    /**
     * Retrieve a single transfer request by ID and status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function getOneByStatus(int $id, string $status): mixed;

    /**
     * Retrieve the latest transfer requests.
     *
     * @return Collection
     */
    public function getLatestTransferRequests(): Collection;

    /**
     * Count pending transfer requests.
     *
     * @return int
     */
    public function countPendingRequests(): int;

    /**
     * Transfer products between branches.
     *
     * @param array $data
     * @return bool
     */
    public function transferRequestProducts(array $data): bool;
}
