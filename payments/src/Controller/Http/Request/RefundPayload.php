<?php

namespace App\Controller\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

class RefundPayload
{
    public function __construct(
        #[Assert\Positive()]
        public ?int $amount = null,
    ) {
    }
}
