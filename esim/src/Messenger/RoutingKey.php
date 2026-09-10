<?php

namespace App\Messenger;

abstract class RoutingKey
{
    public const PAYMENT_SUCCEEDED = 'payment_succeeded';
    public const ESIM_PROVISIONED = 'esim_provisioned';
}
