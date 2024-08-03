<?php

namespace App\Messenger\Message;

class AddInventoryMessage
{
    public function __construct(public string $productId)
    {
    }
}