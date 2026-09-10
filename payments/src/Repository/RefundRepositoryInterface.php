<?php

namespace App\Repository;

use App\Entity\Refund;

interface RefundRepositoryInterface
{
    public function save(Refund $refund): void;
}
