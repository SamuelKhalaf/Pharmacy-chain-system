<?php
namespace App\Services\implementation;

use App\Models\Admin;
use App\Repositories\IAdmin;
use App\Services\IAdminService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service class for managing admin operations.
 */
class AdminService implements IAdminService
{
    /**
     * @var IAdmin The admin repository instance.
     */
    protected IAdmin $adminRepository;

    /**
     * AdminService constructor.
     *
     * @param IAdmin $adminRepository The admin repository implementation.
     */
    public function __construct(IAdmin $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * Retrieve all admins.
     *
     * @return LengthAwarePaginator Paginated collection of admins.
     */
    public function getAllAdmins(): LengthAwarePaginator
    {
        return $this->adminRepository->getAll();
    }

    /**
     * Retrieve admins by role ID.
     *
     * @param int $roleId The role ID to filter admins.
     * @return Collection List of admins with the specified role.
     */
    public function getByRoleId(int $roleId): Collection
    {
        return $this->adminRepository->getBy('role_id', '=', $roleId);
    }

    /**
     * Retrieve a single admin by ID.
     *
     * @param int $id The admin ID.
     * @return Admin|null The admin data or false if not found.
     */
    public function getOneAdmin(int $id): ?Admin
    {
        return $this->adminRepository->findById($id);
    }

    /**
     * Create a new admin.
     *
     * @param array $data The admin data.
     * @return int The ID of the newly created admin.
     */
    public function createAdmin(array $data): int
    {
        if ($data['role_id'] == 1) {
            $data['branch_id'] = null;
        }
        return $this->adminRepository->create($data);
    }

    /**
     * Update an existing admin.
     *
     * @param array $data The updated admin data.
     * @param int $id The admin ID.
     * @return bool Whether the update was successful.
     */
    public function updateAdmin(array $data, int $id): bool
    {
        return $this->adminRepository->update($data, $id);
    }

    /**
     * Delete an admin.
     *
     * @param int $id The admin ID.
     * @return bool Whether the deletion was successful.
     */
    public function deleteAdmin(int $id): bool
    {
        return $this->adminRepository->delete($id);
    }
}
