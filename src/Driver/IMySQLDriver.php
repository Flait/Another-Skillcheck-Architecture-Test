<?php
namespace App\Driver;
use App\Entity\Product;

interface IMySQLDriver
{
    public function findProduct(string $id): Product;
}