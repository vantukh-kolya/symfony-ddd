<?php

namespace App\Catalogue\Presentation\Http\Controller;

use App\Catalogue\Application\Command\CreateProductCommand;
use App\Catalogue\Application\Command\Handler\CreateProductCommandHandler;
use App\Catalogue\Presentation\Http\HttpResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/catalogue')]
class ProductController
{
    public function __construct(private HttpResponseFactory $responseFactory)
    {
    }

    #[Route('/products', name: 'products.create', methods: ['POST'])]
    public function create(CreateProductCommandHandler $handler, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $command = new CreateProductCommand($request->get('name', ''), $request->get('price', 0), $request->get('on_hand', 0));
        $errors = $validator->validate($command);
        if ($errors->count() === 0) {
            $product = $handler($command);
            return $this->responseFactory->success(['id' => $product->getId()], JsonResponse::HTTP_CREATED);
        } else {
            return $this->responseFactory->validationError($errors);
        }
    }
}
