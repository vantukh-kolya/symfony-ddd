<?php

namespace App\Catalogue\Presentation\Http\Controller;

use App\Catalogue\Application\Command\CreateProductCommand;
use App\Catalogue\Application\Command\Handler\CreateProductCommandHandler;
use App\Catalogue\Application\Query\GetProductsQuery;
use App\Catalogue\Application\Query\Handler\GetProductsQueryHandler;
use App\SharedKernel\Http\ResponseEnvelope;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/api/products')]
class ProductController
{

    #[Route('', name: 'products.list', methods: ['GET'])]
    public function listProducts(GetProductsQueryHandler $handler, Request $request): JsonResponse
    {
        $envelope = ResponseEnvelope::success($handler(new GetProductsQuery($request->get('only_available', false), $request->get('max_price'))));
        return new JsonResponse($envelope->body, $envelope->status);
    }

    #[Route('', name: 'products.create', methods: ['POST'])]
    public function create(CreateProductCommandHandler $handler, Request $request): JsonResponse
    {
        $command = new CreateProductCommand(
            Uuid::v7()->toString(), $request->get('name', ''), (int)$request->get('price', 0), (int)$request->get('on_hand', 0)
        );
        $product = $handler($command);
        $envelope = ResponseEnvelope::success(['id' => $product->getId()], JsonResponse::HTTP_CREATED);

        return new JsonResponse($envelope->body, $envelope->status);
    }
}
