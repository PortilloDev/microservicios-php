<?php

namespace App\Service;


class UpdateInventoryAction
{
    public function __construct(public string $inventoryId, public int $quantity)
    {
    }

}