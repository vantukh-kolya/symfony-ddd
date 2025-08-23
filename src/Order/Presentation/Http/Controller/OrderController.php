<?php

namespace App\Order\Presentation\Http\Controller;

use App\Order\Application\Command\CreateOrderCommand;
use App\Order\Application\Command\Handler\CreateOrderCommandHandler;
use App\Order\Presentation\Http\HttpResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/order')]
class OrderController
{
    public function __construct(private HttpResponseFactory $responseFactory)
    {
    }

    #[Route('/orders', name: 'orders.create', methods: ['POST'])]
    public function create(CreateOrderCommandHandler $handler, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $command = new CreateOrderCommand($request->get('amount_to_pay', 0), $request->get('products', []));
        $errors = $validator->validate($command);
        if ($errors->count() === 0) {
            $order = $handler($command);
            return $this->responseFactory->success(['id' => $order->getId()], JsonResponse::HTTP_CREATED);
        } else {
            return $this->responseFactory->validationError($errors);
        }
    }
}
