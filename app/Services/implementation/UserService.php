<?php

namespace App\Services\implementation;

use App\Models\User;
use App\Repositories\IUser;
use App\Services\IUserService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class UserService
 *
 * Handles user management operations.
 */
class UserService implements IUserService
{
    /**
     * @var IUser
     */
    protected IUser $userRepository;

    /**
     * UserService constructor.
     *
     * @param IUser $userRepository
     */
    public function __construct(IUser $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Retrieve all users.
     *
     * @return LengthAwarePaginator
     */
    public function getAllUsers(): LengthAwarePaginator
    {
        return $this->userRepository->getAll();
    }

    /**
     * Retrieve a specific user by ID.
     *
     * @param int $id
     * @return bool|User|null
     */
    public function getOneUser(int $id): bool|null|User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return int
     */
    public function createUser(array $data): int
    {
        return $this->userRepository->create($data);
    }

    /**
     * Update an existing user.
     *
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function updateUser(array $data, int $id): bool
    {
        return $this->userRepository->update($data, $id);
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }
}
