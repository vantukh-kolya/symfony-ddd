<?php

namespace App\Order\Presentation\Http\Controller;

use App\Order\Application\Command\CreateOrderCommand;
use App\Order\Application\Command\Handler\CreateOrderCommandHandler;
use App\Order\Application\Command\Handler\FulfillOrderCommandHandler;
use App\Order\Application\Query\GetOrdersQuery;
use App\Order\Application\Query\Handler\GetOrdersQueryHandler;
use App\Order\Presentation\Http\HttpResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/orders')]
class OrderController
{
    public function __construct(private HttpResponseFactory $responseFactory)
    {
    }

    #[Route('', name: 'orders.list', methods: ['GET'])]
    public function listOrders(GetOrdersQueryHandler $handler, Request $request): JsonResponse
    {
        return $this->responseFactory->success($handler(new GetOrdersQuery($request->get('status'))));
    }

    #[Route('', name: 'orders.create', methods: ['POST'])]
    public function create(CreateOrderCommandHandler $handler, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $command = new CreateOrderCommand(Uuid::v7()->toString(), $request->get('amount_to_pay', 0), $request->get('products', []));
        $errors = $validator->validate($command);
        if ($errors->count() === 0) {
            $order = $handler($command);
            return $this->responseFactory->success(['id' => $order->getId()], JsonResponse::HTTP_CREATED);
        } else {
            return $this->responseFactory->validationError($errors);
        }
    }

    #[Route('/{orderId}/fulfill', name: 'orders.fulfill', methods: ['POST'])]
    public function fulfill(FulfillOrderCommandHandler $handler, Request $request): JsonResponse
    {
        $handler($request->get('orderId'));
        return $this->responseFactory->success();
    }
}
