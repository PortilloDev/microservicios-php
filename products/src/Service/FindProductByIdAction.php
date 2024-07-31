<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class FindProductByIdAction
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function __invoke(Uuid $id): ?Product
    {
        return $this->productRepository->findById($id);
    }

}