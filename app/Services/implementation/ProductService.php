<?php
namespace App\Services\implementation;

use App\Repositories\IProduct;
use App\Services\IProductService;

/**
 *
 */
class ProductService implements IProductService
{
    /**
     * @var IProduct
     */
    protected IProduct $productRepository;

    /**
     * @param IProduct $productRepository
     */
    public function __construct(IProduct $productRepository)
    {
        $this->productRepository = $productRepository;
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
        return $this->productRepository->delete($id);
    }
}
