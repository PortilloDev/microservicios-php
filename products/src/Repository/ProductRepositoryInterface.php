<?php

namespace App\Repository;

use App\Entity\Product;
use Symfony\Component\Uid\UuidV4;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function findById(UuidV4 $id): ?Product;

}