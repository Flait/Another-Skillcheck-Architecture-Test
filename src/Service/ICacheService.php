<?php

namespace App\Service;

use App\Entity\Product;

interface ICacheService
{
    public function findProduct(string $id): ?Product;

    public function saveProduct(string $id, Product $product): void;
}