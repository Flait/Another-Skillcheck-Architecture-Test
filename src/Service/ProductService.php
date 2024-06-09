<?php

namespace App\Service;

use App\Driver\IElasticSearchDriver;
use App\Driver\IMySQLDriver;
use App\Entity\Product;

class ProductService
{
    public function __construct(
        private IElasticSearchDriver $elasticSearchDriver,
        private IMySQLDriver $mySQLDriver,
        private ICacheService $cacheService,
        private string $elasticSearchPercentage,
    )
    {
    }

    public function findProductById(string $id): Product
    {
        // Check cache first
        $cachedProduct = $this->cacheService->findProduct($id);
        if ($cachedProduct) {
            return $cachedProduct;
        }

        $random = mt_rand(1, 100);
        if ($random <= (int) $this->elasticSearchPercentage) {
            $product = $this->elasticSearchDriver->findById($id);
        } else {
            $product = $this->mySQLDriver->findProduct($id);
        }
        // Save product to cache
        $this->cacheService->saveProduct($id, $product);

        return $product;
    }
}