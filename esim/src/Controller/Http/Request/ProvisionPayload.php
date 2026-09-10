<?php

namespace App\Controller\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ProvisionPayload
{
    public function __construct(
        #[Assert\NotBlank()]
        public string $orderId,

        #[Assert\NotBlank()]
        #[Assert\Email()]
        public string $userEmail,

        public string $plan = 'default',

        public ?string $paymentId = null,
    ) {
    }
}
