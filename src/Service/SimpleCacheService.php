<?php

namespace App\Service;

use App\Entity\Product;
use Exception;

class SimpleCacheService implements ICacheService
{
    public function __construct(private string $cacheFile)
    {
    }

    public function findProduct(string $id): ?Product
    {
        if (!file_exists($this->cacheFile)) {
            return null;
        }
        if (!is_string($fileContent = file_get_contents($this->cacheFile))) {
            throw new Exception('Couldnt get cache file');
        }

        $data = json_decode($fileContent, true);

        if (is_array($data) && isset($data[$id])) {
            return new Product($data[$id]);
        }

        return null;
    }

    public function saveProduct(string $id, Product $product): void
    {
        $data = [];
        if (!is_string($fileContent = file_get_contents($this->cacheFile))) {
            throw new Exception('Couldnt get cache file');
        }
        if (file_exists($this->cacheFile)) {
            $data = json_decode($fileContent, true);
            if (!is_array($data)) {
                $data = [];
            }
        }

        // Ensure the product's id is saved as the value corresponding to the id key
        $data[$id] = $product->id;

        file_put_contents($this->cacheFile, json_encode($data, JSON_PRETTY_PRINT));
    }
}