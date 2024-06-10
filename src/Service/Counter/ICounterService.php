<?php

namespace App\Service\Counter;

interface ICounterService
{
    public function incrementSearchCount(string $id): void;
}