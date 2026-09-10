<?php

namespace App\Messenger;

abstract class RoutingKey
{
    public const CHECKOUT_PAYMENT_QUEUE = 'checkout_payment_queue';
    public const PAYMENT_SUCCEEDED = 'payment_succeeded';
    public const PAYMENT_FAILED = 'payment_failed';
}
