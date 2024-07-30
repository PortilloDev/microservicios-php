<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepositoryInterface;

class CreateProductAction
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function __invoke(string $name, float $price, ?string $description): ?Product
    {
        $product = new Product($name, $price, $description);
        $this->productRepository->save($product);
        return $product;
    }

}