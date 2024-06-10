<?php

namespace tests;

use App\Driver\ElasticDriver;
use App\Driver\IElasticSearchDriver;
use App\Driver\IMySQLDriver;
use App\Driver\MySQLDriver;
use App\Entity\Product;
use App\Service\cachce\ICacheService;
use App\Service\cachce\SimpleCacheService;
use App\Service\Counter\ICounterService;
use App\Service\Counter\SimpleCounterService;
use App\Service\ProductService;
use PHPUnit\Framework\TestCase;

class BigApplicationTest extends TestCase
{
    private string $cacheFile;
    private string $counterFile;
    private IElasticSearchDriver $elasticSearchDriver;
    private IMySQLDriver $mySQLDriver;
    private ICacheService $cacheService;
    private ICounterService $counterService;

    protected function setUp(): void
    {
        $this->cacheFile = __DIR__ . DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR .'product_cache_test.json';
        $this->counterFile = __DIR__ . DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR .'product_counter_test.json';

        // Clean up before each test
        if (file_exists($this->cacheFile)) {
            unlink($this->cacheFile);
        }
        fopen($this->cacheFile, "w");
        $this->elasticSearchDriver = new ElasticDriver();
        $this->mySQLDriver = new MySQLDriver();
        $this->cacheService = new SimpleCacheService($this->cacheFile);
        $this->counterService = new SimpleCounterService($this->counterFile);
    }

    public function testFindProductByIdUsesElasticSearch(): void
    {
        $productService = new ProductService(
            $this->elasticSearchDriver,
            $this->mySQLDriver,
            $this->cacheService,
            $this->counterService,
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
            $this->counterService,
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