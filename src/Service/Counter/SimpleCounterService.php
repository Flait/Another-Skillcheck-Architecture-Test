<?php

namespace App\Service\Counter;

use Exception;

class SimpleCounterService implements ICounterService
{
    private string $counterFile;

    public function __construct(string $counterFile)
    {
        $this->counterFile = $counterFile;
    }

    public function incrementSearchCount(string $id): void
    {
        $data = [];

        if (file_exists($this->counterFile)) {
            $fileContent = file_get_contents($this->counterFile);
            if ($fileContent === false) {
                throw new Exception('Could not get counter file');
            }
            $data = json_decode($fileContent, true);
            if (!is_array($data)) {
                $data = [];
            }
        }
        if (!isset($data[$id])) {
            $data[$id] = 0;
        }
        $data[$id] = $data[$id]+1;

        file_put_contents($this->counterFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function getProductHit(string $id): int
    {
        $data = [];

        if (file_exists($this->counterFile)) {
            $fileContent = file_get_contents($this->counterFile);
            if ($fileContent === false) {
                throw new Exception('Could not get counter file');
            }
            $data = json_decode($fileContent, true);
            if (!is_array($data)) {
                $data = [];
            }
        }

        return $data[$id] ?? 0;
    }
}