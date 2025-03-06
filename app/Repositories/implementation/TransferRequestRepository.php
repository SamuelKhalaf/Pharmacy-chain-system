<?php
namespace App\Repositories\implementation;

use App\Models\TransferRequest;
use App\Repositories\ITransferRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TransferRequestRepository implements ITransferRequest
{
    /**
     * Get all transfer requests with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return TransferRequest::paginate(PAGINATE_COUNT);
    }

    /**
     * Find a transfer request by ID.
     *
     * @param int $id
     * @return TransferRequest|bool|null
     */
    public function findById(int $id): TransferRequest|bool|null
    {
        if ($this->isExists($id)) {
            return TransferRequest::where('id', $id)->first();
        }
        return false;
    }

    /**
     * Create a new transfer request.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int
    {
        return TransferRequest::create([
            'from_branch_id' => $data['from_branch_id'],
            'to_branch_id' => $data['to_branch_id'],
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'status' => 'pending'
        ])->id;
    }

    /**
     * Update an existing transfer request.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool
    {
        if ($this->isExists($id)) {
            return TransferRequest::where('id', $id)->update($data);
        }
        return false;
    }

    /**
     * Delete a transfer request by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        if ($this->isExists($id)) {
            return TransferRequest::where('id', $id)->delete();
        }
        return false;
    }

    /**
     * Check if a transfer request exists by ID.
     *
     * @param int $id
     * @return bool
     */
    public function isExists(int $id): bool
    {
        return TransferRequest::where('id', $id)->exists();
    }

    /**
     * Get all transfer requests with a specific status.
     *
     * @param string $status
     * @return Collection
     */
    public function getAllPendingRequests(string $status): Collection
    {
        return TransferRequest::where('status', trim($status))->get();
    }

    /**
     * Get the latest five pending transfer requests with branch and product details.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getLatestTransferRequests(): \Illuminate\Support\Collection
    {
        return DB::table('transfer_requests as t')
            ->join('branches as fb', 't.from_branch_id', '=', 'fb.id')
            ->join('branches as tb', 't.to_branch_id', '=', 'tb.id')
            ->join('products as p', 't.product_id', '=', 'p.id')
            ->select(
                't.*',
                'fb.name as from_branch_name',
                'tb.name as to_branch_name',
                'p.name as product_name',
            )
            ->where('t.status', 'pending')
            ->latest('t.created_at')
            ->limit(5)
            ->get();
    }

    /**
     * Count the number of pending transfer requests.
     *
     * @return int
     */
    public function countPendingRequests(): int
    {
        return TransferRequest::where('status', 'pending')->count();
    }

    /**
     * Get a transfer request by ID and status.
     *
     * @param int $id
     * @param string $status
     * @return object|null
     */
    public function getOneByStatus(int $id, string $status): ?object
    {
        return TransferRequest::query()
            ->where('id', $id)
            ->where('status', trim($status))
            ->first();
    }
}
