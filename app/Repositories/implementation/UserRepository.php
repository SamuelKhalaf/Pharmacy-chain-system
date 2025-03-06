<?php
namespace App\Repositories\implementation;

use App\Models\User;
use App\Repositories\IUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements IUser
{
    /**
     * Get all users with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function getAll(): LengthAwarePaginator
    {
        return User::paginate(PAGINATE_COUNT);
    }

    /**
     * Get users by a specific column condition.
     *
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return Collection
     */
    public function getBy(string $column, string $operator, mixed $value): Collection
    {
        return User::query()
            ->where($column, $operator, $value)
            ->get();
    }

    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return User|bool|null
     */
    public function findById(int $id): User|bool|null
    {
        if ($this->isExists($id)) {
            return User::where('id', $id)->first();
        }
        return false;
    }

    /**
     * Create a new user and return the ID.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int
    {
        return User::create($data)->id;
    }

    /**
     * Update an existing user by ID.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, int $id): bool
    {
        if ($this->isExists($id)) {
            return User::where('id', $id)->update($data);
        }
        return false;
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        if ($this->isExists($id)) {
            return User::where('id', $id)->delete();
        }
        return false;
    }

    /**
     * Check if a user exists by ID.
     *
     * @param int $id
     * @return bool
     */
    public function isExists(int $id): bool
    {
        return User::where('id', $id)->exists();
    }
}
