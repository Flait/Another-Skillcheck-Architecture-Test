<?php

namespace Controller;

use App\Controller\ProductController;
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

class ProductControllerTest extends TestCase
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
            //unlink($this->cacheFile);
        }
        fopen($this->cacheFile, "w");
        if (file_exists($this->counterFile)) {
            //unlink($this->counterFile);
        }
        fopen($this->counterFile, "w");
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
        $controller = new ProductController($productService);

        $product = $controller->detail('1');
        $this->assertEquals('{"id":"1"}', $product);

        $product = $controller->detail('1');
        $this->assertEquals('{"id":"1"}', $product);

        $product = $controller->detail('2');
        $this->assertEquals('{"id":"2"}', $product);
        $this->assertEquals(2, $this->counterService->getProductHit('1'));
    }

    public function testFindProductByIdUsesMySQLSearch(): void
    {
        $productService = new ProductService(
            $this->elasticSearchDriver,
            $this->mySQLDriver,
            $this->cacheService,
            $this->counterService,
            '0'
        );
        $controller = new ProductController($productService);

        $product = $controller->detail('1');
        $this->assertEquals('{"id":"1"}', $product);

        $product = $controller->detail('1');
        $this->assertEquals('{"id":"1"}', $product);

        $product = $controller->detail('2');
        $this->assertEquals('{"id":"2"}', $product);
        $this->assertEquals(2, $this->counterService->getProductHit('1'));
    }

    protected function tearDown(): void
    {
        // Clean up after each test
        if (file_exists($this->cacheFile)) {
            // unlink($this->cacheFile);
        }
    }

}