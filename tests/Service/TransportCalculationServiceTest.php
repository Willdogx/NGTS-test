<?php

declare(strict_types=1);

namespace Test\Service;

use App\Data\Repository\InMemoryCityRepository;
use App\Exception\CityNotFoundException;
use App\Model\City;
use App\Model\Connection;
use App\Model\Itinerary;
use App\Service\TransportCalculationService;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * @covers TransportCalculationService
 */
class TransportCalculationServiceTest extends TestCase
{
    public function testCalculateItinerariesWithCheapestPriceReturnsExpectedListOfItineraries(): void
    {
        $cityRepository = new InMemoryCityRepository(__DIR__ . '/../../src/Data/cities.json');

        $transportCalculationService = new TransportCalculationService($cityRepository);
        $itinerary = new Itinerary(
            new City(
                'Logroño',
                [
                    new Connection('Zaragoza', 4),
                    new Connection('Teruel', 6),
                    new Connection('Madrid', 8)
                ]
            )
        );
        $itinerary
            ->addStop(new Connection('Zaragoza', 4))
            ->addStop(new Connection('Lleida', 2))
            ->addStop(new Connection('Castellón', 4))
            ->addStop(new Connection('Ciudad Real', 6))
        ;
        $expected = [$itinerary];

        $this->assertEquals($expected, $transportCalculationService->calculateItinerariesWithCheapestPrice('Logroño', 'Ciudad Real'));
    }

    public function testCalculateItinerariesWithCheapestPriceReturnsItineraryWithOriginWhenOriginAndDestinationAreTheSame(): void
    {
        $cityRepository = new InMemoryCityRepository(__DIR__ . '/../../src/Data/cities.json');

        $transportCalculationService = new TransportCalculationService($cityRepository);
        $itinerary = new Itinerary(
            new City(
                'Logroño',
                [
                    new Connection('Zaragoza', 4),
                    new Connection('Teruel', 6),
                    new Connection('Madrid', 8)
                ]
            )
        );
        $expected = [$itinerary];

        $this->assertEquals($expected, $transportCalculationService->calculateItinerariesWithCheapestPrice('Logroño', 'Logroño'));
    }

    /**
     * @dataProvider citiesProvider
     */
    public function testCalculateItinerariesWithCheapestPriceThrowsCityNotFoundExceptionWhenGivenWrongInputs(string $departure, string $destination): void
    {
        $cityRepository = new InMemoryCityRepository(__DIR__ . '/../../src/Data/cities.json');
        $transportCalculationService = new TransportCalculationService($cityRepository);

        $this->expectException(CityNotFoundException::class);
        $transportCalculationService->calculateItinerariesWithCheapestPrice($departure, $destination);
    }

    /**
     * @return array<string[]>
     */
    public function citiesProvider(): array
    {
        return [
            ['Logroño', 'foo'],
            ['bar', 'Ciudad Real']
        ];
    }

    public function testCalculateItinerariesWithCheapestPriceThrowsUnexpectedValueExceptionIfLastConnectionIsNotACity(): void
    {
        $cityRepository = new InMemoryCityRepository(__DIR__ . '/cities.json');
        $transportCalculationService = new TransportCalculationService($cityRepository);

        $this->expectException(UnexpectedValueException::class);
        $transportCalculationService->calculateItinerariesWithCheapestPrice('Logroño', 'Zaragoza');
    }
}
