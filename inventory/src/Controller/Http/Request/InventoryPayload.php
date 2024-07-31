<?php
namespace App\Controller\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

class InventoryPayload
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\NotNull()]
        #[Assert\Uuid()]
        public string $productId,

        #[Assert\NotBlank()]
        public int $quantity,
    ) {
    }
}