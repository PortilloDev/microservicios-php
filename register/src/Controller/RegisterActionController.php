<?php

namespace App\Controller;

use App\Controller\Http\Request\RegisterPayload;
use App\Service\RegisterActionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class RegisterActionController extends AbstractController
{
    public function __construct(public RegisterActionService $registerActionService)
    {
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload]RegisterPayload $request): JsonResponse
    {
        $this->registerActionService->__invoke($request->name, $request->email);
        return new JsonResponse();
    }
}