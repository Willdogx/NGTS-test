<?php

declare(strict_types=1);

namespace App\Factory;

use App\Data\Repository\InMemoryCityRepository;
use App\Service\TransportCalculationService;

class TransportCalculationServiceFactory
{
    public function getService(): TransportCalculationService
    {
        $cityRepository = new InMemoryCityRepository(__DIR__ . '/../Data/cities.json');
        return new TransportCalculationService($cityRepository);
    }
}
