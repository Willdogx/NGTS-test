<?php

declare(strict_types=1);

use App\Factory\InMemoryCityRepositoryFactory;
use App\Factory\TransportCalculationServiceFactory;

require __DIR__ . '/vendor/autoload.php';

$transportCalculationService = (new TransportCalculationServiceFactory())->getService();

$cheapestPrices = $transportCalculationService->calculateItinerariesWithCheapestPrice('Logroño', 'Ciudad Real');

var_dump((string) $cheapestPrices[0]);

echo PHP_EOL . PHP_EOL;

$cityRepository = (new InMemoryCityRepositoryFactory())->getRepository();
$cities = $cityRepository->getCities();

foreach ($cities as $city) {
    $cheapestPrices = $transportCalculationService->calculateItinerariesWithCheapestPrice('Logroño', $city->getName());
    var_dump((string) $cheapestPrices[0]);
}