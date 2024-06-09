<?php

namespace App\Driver;

use App\Entity\Product;

class ElasticDriver implements IElasticSearchDriver
{
    public function findById(string $id): Product
    {
        return new Product($id);
    }
}