<?php

namespace App\Messenger;

abstract class RoutingKey
{
    public const UPDATE_INVENTORY_QUEUE = 'update_inventory_queue';
    public const MAILER_STOCK_OUT_QUEUE = 'mailer_stock_out_queue';
}