<?php

namespace App\Tests\Service;

use App\Driver\ElasticDriver;
use App\Driver\IElasticSearchDriver;
use App\Driver\IMySQLDriver;
use App\Driver\MySQLDriver;
use App\Service\ICacheService;
use App\Service\ProductService;
use App\Service\SimpleCacheService;
use PHPUnit\Framework\TestCase;
use App\Entity\Product;

class ProductServiceTest extends TestCase
{
    private string $cacheFile;
    private IElasticSearchDriver $elasticSearchDriver;
    private IMySQLDriver $mySQLDriver;
    private ICacheService $cacheService;

    protected function setUp(): void
    {
        $this->cacheFile = __DIR__ . DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR .'product_cache_test.json';

        // Clean up before each test
        if (file_exists($this->cacheFile)) {
            unlink($this->cacheFile);
        }
        fopen($this->cacheFile, "w");
        $this->elasticSearchDriver = new ElasticDriver();
        $this->mySQLDriver = new MySQLDriver();
        $this->cacheService = new SimpleCacheService($this->cacheFile);
    }

    public function testFindProductByIdUsesElasticSearch(): void
    {
        $productService = new ProductService(
            $this->elasticSearchDriver,
            $this->mySQLDriver,
            $this->cacheService,
            '100'
        );

        $product = $productService->findProductById('1');
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('1', $product->id);

        $productFromCache = $productService->findProductById('1');
        $this->assertInstanceOf(Product::class, $productFromCache);
        $this->assertEquals('1', $productFromCache->id);

        $product = $productService->findProductById('2');
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('2', $product->id);

        $productFromCache = $productService->findProductById('2');
        $this->assertInstanceOf(Product::class, $productFromCache);
        $this->assertEquals('2', $productFromCache->id);
    }

    public function testFindProductByIdUsesMySQL(): void
    {
        $productService = new ProductService(
            $this->elasticSearchDriver,
            $this->mySQLDriver,
            $this->cacheService,
            '0'
        );

        $product = $productService->findProductById('1');
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('1', $product->id);

        $productFromCache = $productService->findProductById('1');
        $this->assertInstanceOf(Product::class, $productFromCache);
        $this->assertEquals('1', $productFromCache->id);

        $product = $productService->findProductById('2');
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('2', $product->id);

        $productFromCache = $productService->findProductById('2');
        $this->assertInstanceOf(Product::class, $productFromCache);
        $this->assertEquals('2', $productFromCache->id);
    }

    protected function tearDown(): void
    {
        // Clean up after each test
        if (file_exists($this->cacheFile)) {
           // unlink($this->cacheFile);
        }
    }
}