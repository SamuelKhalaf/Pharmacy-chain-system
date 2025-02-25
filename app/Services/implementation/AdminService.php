<?php
namespace App\Services\implementation;

use App\Repositories\IAdmin;
use App\Services\IAdminService;

/**
 *
 */
class AdminService implements IAdminService
{
    /**
     * @var IAdmin
     */
    protected IAdmin $adminRepository;

    /**
     * @param IAdmin $adminRepository
     */
    public function __construct(IAdmin $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * @return mixed
     */
    public function getAllAdmins()
    {
        return $this->adminRepository->getAll();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getOneAdmin($id)
    {
        return $this->adminRepository->findById($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createAdmin(array $data)
    {
        if($data['role_id'] == 1){
            $data['branch_id'] = null;
        }
        return $this->adminRepository->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function updateAdmin(array $data, $id)
    {
        return $this->adminRepository->update($data, $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteAdmin($id)
    {
        return $this->adminRepository->delete($id);
    }
}
