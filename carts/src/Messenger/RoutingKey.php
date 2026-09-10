<?php

namespace App\Messenger;

abstract class RoutingKey
{
    public const UPDATE_INVENTORY_QUEUE = 'update_inventory_queue';
    public const CHECKOUT_PAYMENT_QUEUE = 'checkout_payment_queue';
    public const PAYMENT_SUCCEEDED = 'payment_succeeded';
    public const PAYMENT_FAILED = 'payment_failed';
}
