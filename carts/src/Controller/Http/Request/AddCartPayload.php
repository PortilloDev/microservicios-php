<?php
namespace App\Controller\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

class AddCartPayload
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\NotNull()]
        #[Assert\Uuid()]
        public string $productId,

        #[Assert\NotBlank()]
        #[Assert\NotNull()]
        public string $userEmail,

        #[Assert\NotBlank()]
        public int $quantity,
    ) {
    }
}