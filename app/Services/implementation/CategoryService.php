<?php
namespace App\Services\implementation;

use App\Repositories\ICategory;
use App\Services\ICategoryService;
/**
 *
 */
class CategoryService implements ICategoryService
{
    /**
     * @var ICategory
     */
    protected ICategory $categoryRepository;

    /**
     * @param ICategory $categoryRepository
     */
    public function __construct(ICategory $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return mixed
     */
    public function getAllCategories()
    {
        return $this->categoryRepository->getAll();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getOneCategory($id)
    {
        return $this->categoryRepository->findById($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createCategory(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function updateCategory(array $data, $id)
    {
        return $this->categoryRepository->update($data, $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteCategory($id)
    {
        return $this->categoryRepository->delete($id);
    }
}
