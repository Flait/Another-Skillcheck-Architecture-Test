<?php

namespace App\Service\cachce;

use App\Entity\Product;

interface ICacheService
{
    public function findProduct(string $id): ?Product;

    public function saveProduct(string $id, Product $product): void;
}