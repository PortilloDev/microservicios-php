<?php

namespace App\Controller\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProductPayload
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Length(min: 2, max: 20)]
        public string $name,

        #[Assert\NotBlank()]
        public float $price,

        #[Assert\Length(max: 250)]
        public ?string $description,
    ) {
    }
}