<?php

declare(strict_types=1);

namespace App\Data\Repository;

use App\Exception\FileNotFoundException;
use App\Model\City;
use App\Model\Connection;
use RuntimeException;
use function file_exists;
use function file_get_contents;
use function json_decode;

class InMemoryCityRepository implements CityRepositoryInterface
{
    /**
     * @var array[]
     */
    private array $cities;


    public function __construct(string $dataFixtureFilename)
    {
        $citiesJson = @file_get_contents($dataFixtureFilename);
        if ($citiesJson === false) {
            throw new FileNotFoundException("data fixture file `$dataFixtureFilename` does not exist.");
        }

        $this->cities = json_decode($citiesJson, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @return City[]
     */
    public function getCities(): array
    {
        $cityModels = [];
        foreach ($this->cities as $city) {
            $cityModels[] = new City(
                $city['name'],
                array_map(fn (array $connection) => new Connection($connection['name'], $connection['price']), $city['connections'])
            );
        }

        return $cityModels;
    }
}
