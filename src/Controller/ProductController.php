<?php

namespace App\Controller;

use App\Service\ProductService;

class ProductController
{
    public function __construct(
        private ProductService $productService
    )
    {
    }

    public function detail(string $id): string
    {
        if (is_string($decode = json_encode($this->productService->findProductById($id)))) {
            return $decode;
        }
        throw new \Exception('Failed to transfer product into string');
    }
}