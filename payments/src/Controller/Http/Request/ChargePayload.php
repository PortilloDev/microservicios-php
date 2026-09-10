<?php

namespace App\Controller\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ChargePayload
{
    public function __construct(
        #[Assert\NotBlank()]
        public string $orderId,

        #[Assert\NotBlank()]
        #[Assert\Email()]
        public string $userEmail,

        #[Assert\NotBlank()]
        #[Assert\Positive()]
        public int $amount,

        #[Assert\NotBlank()]
        public string $currency = 'EUR',

        public string $paymentMethod = 'tok_visa',
    ) {
    }
}
