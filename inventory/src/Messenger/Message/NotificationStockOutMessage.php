<?php

namespace App\Messenger\Message;

class NotificationStockOutMessage
{
    public function __construct(public string $productId, public int $quantity)
    {
    }
}