<?php

namespace App\Repository;

use App\Entity\Cart;

interface CartRepositoryInterface
{
    public function findById(string $id): ?Cart;
    public function findByUserAndOpenCart(string $userEmail, string $status): ?Cart;
    public function save(Cart $cart): void;
}