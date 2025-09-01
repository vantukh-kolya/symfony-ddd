<?php

namespace App\Order\Presentation\Http\Controller;

use App\Order\Application\Command\CreateOrderCommand;
use App\Order\Application\Command\Handler\CreateOrderCommandHandler;
use App\Order\Application\Command\Handler\FulfillOrderCommandHandler;
use App\Order\Application\Query\GetOrdersQuery;
use App\Order\Application\Query\Handler\GetOrdersQueryHandler;
use App\SharedKernel\Http\ResponseEnvelope;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/api/orders')]
class OrderController
{

    #[Route('', name: 'orders.list', methods: ['GET'])]
    public function listOrders(GetOrdersQueryHandler $handler, Request $request): JsonResponse
    {
        $envelope = ResponseEnvelope::success($handler(new GetOrdersQuery($request->get('status'))));

        return new JsonResponse($envelope->body, $envelope->status);
    }

    #[Route('', name: 'orders.create', methods: ['POST'])]
    public function create(CreateOrderCommandHandler $handler, Request $request): JsonResponse
    {
        $command = new CreateOrderCommand(Uuid::v7()->toString(), $request->get('amount_to_pay', 0), $request->get('products', []));

        $order = $handler($command);
        $envelope = ResponseEnvelope::success(['id' => $order->getId()], JsonResponse::HTTP_CREATED);
        return new JsonResponse($envelope->body, $envelope->status);
    }

    #[Route('/{orderId}/fulfill', name: 'orders.fulfill', methods: ['POST'])]
    public function fulfill(FulfillOrderCommandHandler $handler, Request $request): JsonResponse
    {
        $handler($request->get('orderId'));
        $envelope = ResponseEnvelope::success();
        return new JsonResponse($envelope->body, $envelope->status);
    }
}
