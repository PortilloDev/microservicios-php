<?php

namespace App\Messenger\Message;

class UpdateInventoryMessage
{
    public function __construct(public string $productId, public int $quantity)
    {
    }

}