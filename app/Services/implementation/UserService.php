<?php
namespace App\Services\implementation;

use App\Enums\UserRole;
use App\Repositories\implementation\RoleRepository;
use App\Repositories\IUser;
use App\Services\IRoleService;
use App\Services\IUserService;

/**
 *
 */
class UserService implements IUserService
{
    /**
     * @var IUser
     */
    protected IUser $userRepository;

    /**
     * @param IUser $userRepository
     */
    public function __construct(IUser $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return mixed
     */
    public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }

    /**
     * @return mixed
     */
    public function getAllModerators()
    {
        return $this->userRepository->getBy('role_id','!=',UserRole::Customer);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getOneUser($id)
    {
        return $this->userRepository->findById($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createUser(array $data)
    {
        return $this->userRepository->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function updateUser(array $data, $id)
    {
        return $this->userRepository->update($data, $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
    }
}
