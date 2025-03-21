<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface IBranchService
{
    /**
     * Get all branches.
     *
     * @return LengthAwarePaginator
     */
    public function getAllBranches(): LengthAwarePaginator;

    /**
     * Get all branches without the authenticated admin branch.
     *
     * @param $branch_id
     * @return Collection
     */
    public function getWhereNotIn($branch_id): Collection;

    /**
     * Get other old branches without the authenticated admin branch.
     *
     * @return Collection
     */
    public function getOtherOldBranches(): Collection;

    /**
     * Get newly created branches.
     *
     * @return Collection
     */
    public function getNewBranches(): Collection;

    /**
     * Get old branches.
     *
     * @return Collection
     */
    public function getOldBranches(): Collection;

    /**
     * Get a single branch by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getOneBranch(int $id): mixed;

    /**
     * Create a new branch.
     *
     * @param array $data
     * @return int
     */
    public function createBranch(array $data): int;

    /**
     * Update an existing branch.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateBranch(array $data, int $id): bool;

    /**
     * Delete a branch and all its inventory products.
     *
     * @param int $id
     * @return bool
     */
    public function deleteBranch(int $id): bool;
}
