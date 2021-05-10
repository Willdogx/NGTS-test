<?php

declare(strict_types=1);

namespace Test\Data\Repository;

use App\Data\Repository\InMemoryCityRepository;
use App\Exception\FileNotFoundException;
use App\Model\City;
use App\Model\Connection;
use PHPUnit\Framework\TestCase;

/**
 * @covers InMemoryCityRepository
 */
class InMemoryCityRepositoryTest extends TestCase
{
    public function testConstructorThrowsFileNotFoundExceptionWhenGivenWrongFilename(): void
    {
        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage('data fixture file `non-existent-file` does not exist.');
        $inMemoryCityRepository = new InMemoryCityRepository('non-existent-file');       
    }

    public function testGetCitiesReturnsArrayOfCityModels(): void
    {
        $inMemoryCityRepository = new InMemoryCityRepository(__DIR__ . '/cities.json');       
        $expectedCities = [
            new City('Logroño', [new Connection('Zaragoza', 4), new Connection('Teruel', 6), new Connection('Madrid', 8)]),
            new City('Zaragoza', [new Connection('Logroño', 4), new Connection('Teruel', 2), new Connection('Lleida', 2)])
        ];

        $this->assertEquals($expectedCities, $inMemoryCityRepository->getCities());
    }
}
