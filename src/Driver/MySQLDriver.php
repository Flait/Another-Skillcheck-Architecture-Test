<?php

namespace App\Driver;

use App\Entity\Product;

final class MySQLDriver implements IMySQLDriver
{
    public function findProduct(string $id): Product
    {
        return new Product($id);
    }
}