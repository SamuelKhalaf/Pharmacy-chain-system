<?php
namespace App\Services\implementation;

use App\Repositories\ICategory;
use App\Repositories\IProduct;
use App\Services\ICategoryService;
use App\Services\IProductService;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class CategoryService implements ICategoryService
{
    /**
     * @var ICategory
     */
    protected ICategory $categoryRepository;
    protected IProductService $productService;

    /**
     * @param ICategory $categoryRepository
     */
    public function __construct(ICategory $categoryRepository,IProductService $productService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productService = $productService;
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
        try {
            DB::beginTransaction();
            $this->productService->deleteProductByCategoryId($id);

            $this->categoryRepository->delete($id);

            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollBack();
            return false;
        }
    }
}
