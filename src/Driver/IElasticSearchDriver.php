<?php
namespace App\Driver;
use App\Entity\Product;

interface IElasticSearchDriver
{
    public function findById(string $id): Product;
}