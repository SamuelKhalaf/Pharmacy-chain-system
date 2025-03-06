<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface IBranch
{
    /**
     * Get all branches with pagination.
     *
     * @return mixed
     */
    public function getAll(): mixed;

    /**
     * Get all branches that don't have inventory.
     *
     * @return Collection
     */
    public function getNewBranches(): Collection;

    /**
     * Get all branches that have inventory.
     *
     * @return Collection
     */
    public function getOldBranches(): Collection;

    /**
     * Get all branches that have inventory except the authenticated user's branch.
     *
     * @return Collection
     */
    public function getOtherOldBranches(): Collection;

    /**
     * Get all branches that are not in the given product IDs list.
     *
     * @param array|int $productIds
     * @return Collection
     */
    public function getWhereNotIn(array|int $productIds): Collection;

    /**
     * Find a branch by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed;

    /**
     * Create a new branch and return its ID.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int;

    /**
     * Update a branch.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool;

    /**
     * Delete a branch by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
