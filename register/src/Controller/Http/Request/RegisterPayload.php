<?php

namespace App\Controller\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterPayload
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Length(min: 2)]
        public string $name,

        #[Assert\NotBlank()]
        #[Assert\Email()]
        public string $email,
    ) {
    }
}