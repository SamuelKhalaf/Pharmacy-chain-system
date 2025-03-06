<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\TransferRequest;

interface ITransferRequest
{
    /**
     * Get all transfer requests with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator;

    /**
     * Find a transfer request by ID.
     *
     * @param int $id
     * @return TransferRequest|bool|null
     */
    public function findById(int $id): TransferRequest|bool|null;

    /**
     * Create a new transfer request.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int;

    /**
     * Update an existing transfer request.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool;

    /**
     * Delete a transfer request by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Get all transfer requests with a specific status.
     *
     * @param string $status
     * @return Collection
     */
    public function getAllPendingRequests(string $status): Collection;

    /**
     * Get a transfer request by ID and status.
     *
     * @param int $id
     * @param string $status
     * @return object|null
     */
    public function getOneByStatus(int $id, string $status): ?object;

    /**
     * Get the latest five pending transfer requests with branch and product details.
     *
     * @return Collection
     */
    public function getLatestTransferRequests(): \Illuminate\Support\Collection;

    /**
     * Count the number of pending transfer requests.
     *
     * @return int
     */
    public function countPendingRequests(): int;
}
