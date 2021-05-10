<?php

declare(strict_types=1);

namespace App\Factory;

use App\Data\Repository\InMemoryCityRepository;

class InMemoryCityRepositoryFactory
{
    public function getRepository(): InMemoryCityRepository
    {
        return new InMemoryCityRepository(__DIR__ . '/../Data/cities.json');
    }
}
