<?php

namespace App\Service;

use App\Driver\IElasticSearchDriver;
use App\Driver\IMySQLDriver;
use App\Entity\Product;
use App\Service\cachce\ICacheService;
use App\Service\Counter\ICounterService;

class ProductService
{
    public function __construct(
        private IElasticSearchDriver $elasticSearchDriver,
        private IMySQLDriver $mySQLDriver,
        private ICacheService $cacheService,
        private ICounterService $counterService,
        private string $elasticSearchPercentage,
    )
    {
    }

    public function findProductById(string $id): Product
    {
        // Check cache first
        $cachedProduct = $this->cacheService->findProduct($id);
        if ($cachedProduct) {
            $this->counterService->incrementSearchCount($id);
            return $cachedProduct;
        }

        $random = mt_rand(1, 100);
        if ($random <= (int) $this->elasticSearchPercentage) {
            $product = $this->elasticSearchDriver->findById($id);
        } else {
            $product = $this->mySQLDriver->findProduct($id);
        }
        $this->counterService->incrementSearchCount($id);
        $this->cacheService->saveProduct($id, $product);

        return $product;
    }
}