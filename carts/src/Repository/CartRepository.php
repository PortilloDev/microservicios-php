<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CartRepository extends ServiceEntityRepository implements CartRepositoryInterface
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Cart::class);
    }
    public function findById(string $id): ?Cart
    {
        return $this->find($id);
    }
    public function findByUserAndOpenCart(string $userEmail, string $status): ?Cart
    {
        return $this->findOneBy(['userEmail' => $userEmail, 'status' => $status]);
    }

    public function save(Cart $cart): void
    {
        $this->getEntityManager()->persist($cart);
        $this->getEntityManager()->flush();
    }
}