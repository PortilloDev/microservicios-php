<?php

namespace App\Controller;

use App\Controller\Http\Request\CreateProductPayload;
use App\Service\CreateProductAction;
use App\Service\FindProductByIdAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class FindProductByIdController extends AbstractController
{
    public function __construct(private FindProductByIdAction $findProductByIdAction)
    {
    }

    #[Route('/api/internal/product/{id}', name: 'api_internal_find_product', methods: ['GET'])]
    public function __invoke(Request $request, string $id): JsonResponse
    {

        if (null === $product = $this->findProductByIdAction->__invoke(Uuid::fromString($id))) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        return new JsonResponse($product->toArray());

    }
}