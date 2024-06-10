<?php

namespace App\Driver;

use App\Entity\Product;

final class ElasticDriver implements IElasticSearchDriver
{
    public function findById(string $id): Product
    {
        return new Product($id);
    }
}