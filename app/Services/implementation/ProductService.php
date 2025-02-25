<?php
namespace App\Services\implementation;

use App\Repositories\IBranchInventory;
use App\Repositories\IProduct;
use App\Services\IProductService;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class ProductService implements IProductService
{
    /**
     * @var IProduct
     */
    protected IProduct $productRepository;
    protected IBranchInventory $branchInventoryRepository;

    /**
     * @param IProduct $productRepository
     * @param IBranchInventory $branchInventoryRepository
     */
    public function __construct(IProduct $productRepository,IBranchInventory $branchInventoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->branchInventoryRepository = $branchInventoryRepository;
    }

    /**
     * @return mixed
     */
    public function getAllProducts()
    {
        return $this->productRepository->getAll();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getOneProduct($id)
    {
        return $this->productRepository->findById($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createProduct(array $data)
    {
        return $this->productRepository->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function updateProduct(array $data, $id)
    {
        return $this->productRepository->update($data, $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteProduct($id)
    {
        try {
            DB::beginTransaction();

            $this->branchInventoryRepository->deleteSpecificProductsFromAllInventories($id);
            $this->productRepository->delete($id);

            DB::commit();
            return true;

        }catch (\Exception $e){
            DB::rollBack();
            return false;
        }
    }

    public function deleteProductByCategoryId($category_id)
    {
        try {
            DB::beginTransaction();
            $productIds = $this->productRepository->deleteProductsByCategoryId($category_id);

            $this->branchInventoryRepository->deleteSpecificProductsFromAllInventories($productIds);

            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollBack();
            return false;
        }
    }
}
