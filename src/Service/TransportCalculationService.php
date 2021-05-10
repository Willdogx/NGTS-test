<?php

declare(strict_types=1);

namespace App\Service;

use App\Data\Repository\CityRepositoryInterface;
use App\Exception\CityNotFoundException;
use App\Model\City;
use App\Model\Connection;
use App\Model\Itinerary;
use UnexpectedValueException;
use function array_filter;
use function array_map;
use function array_pop;
use function array_slice;
use function in_array;

class TransportCalculationService
{
    private CityRepositoryInterface $cityRepository;

    public function __construct(CityRepositoryInterface $cityRepositoryInterface)
    {
        $this->cityRepository = $cityRepositoryInterface;
    }

    /**
     * @return Itinerary[]
     */
    public function calculateItinerariesWithCheapestPrice(string $departure, string $destination): array
    {
        $cities = $this->cityRepository->getCities();

        $departureCity = $this->filterCity($cities, $departure);
        if ($departureCity === null) {
            throw new CityNotFoundException("City `$departure` does not exist.");
        }
        $destinationCity = $this->filterCity($cities, $destination);
        if ($destinationCity === null) {
            throw new CityNotFoundException("City `$destination` does not exist.");
        }

        if ($departure === $destination) {
            return [new Itinerary($departureCity)];
        }

        $itinerariesInProcess = [];
        $finishedItineraries = [];
        $connections = $departureCity->getConnections();
        foreach ($connections as $connection) {
            $itinerary = new Itinerary($departureCity);
            $itinerary->addStop($connection);
            $itinerariesInProcess[] = $itinerary;
        }

        while ($itinerariesInProcess) {
            foreach ($itinerariesInProcess as $itineraryKey => $itinerary) {
                // if last connection is the destination, then save it in the finished itineraries and discard it from the ones being processed
                $lastConnectionInItinerary = array_slice($itinerary->getStops(), -1)[0];
                if ($lastConnectionInItinerary->getName() === $destinationCity->getName()) {
                    $finishedItineraries[] = $itinerary;
                    unset($itinerariesInProcess[$itineraryKey]);
                    continue;
                }


                $lastCity = $this->filterCity($cities, $lastConnectionInItinerary->getName());
                if ($lastCity === null) {
                    throw new UnexpectedValueException('Could not find city for a given connection.');
                }

                $lastCityConnections = $this->filterAllConnectionsAlreadyPresentInItinerary($lastCity, $itinerary);
                // if filtered connections is empty, then discard the itinerary
                if (!$lastCityConnections) {
                    unset($itinerariesInProcess[$itineraryKey]);
                    continue;
                }

                // create new itineraries for each new connection and remove discontinued one
                $itinerariesInProcess = $this->createNewItinerariesForEachNewConnection($lastCityConnections, $itinerariesInProcess, $itinerary);
                unset($itinerariesInProcess[$itineraryKey]);
            }
        }

        $cheapestPrice = array_reduce(
            $finishedItineraries,
            fn (?int $carry, Itinerary $itinerary) => $carry === null
                ? $itinerary->getTotalCost()
                : min($itinerary->getTotalCost(), $carry)
        );

        $itinerariesWithCheapestPrice = array_filter($finishedItineraries, fn (Itinerary $itinerary) => $itinerary->getTotalCost() === $cheapestPrice);

        return array_values($itinerariesWithCheapestPrice);
    }

    /**
     * @param City[] $cities
     */
    private function filterCity(array $cities, string $cityName): ?City
    {
        $filter = array_filter($cities, fn (City $city) => $city->getName() === $cityName);

        return array_pop($filter);
    }

    /**
     * @return Connection[]
     */
    private function filterAllConnectionsAlreadyPresentInItinerary(City $lastCity, Itinerary $itinerary): array
    {
        $lastCityConnections = $lastCity->getConnections();

        return array_filter(
            $lastCityConnections,
            fn (Connection $connection) => !in_array(
                $connection->getName(),
                array_map(fn (Connection $connection) => $connection->getName(), $itinerary->getStops()),
                true
            )
        );
    }

    /**
     * @param Connection[] $lastCityConnections
     * @param Itinerary[] $itinerariesInProcess
     * @return Itinerary[]
     */
    private function createNewItinerariesForEachNewConnection(array $lastCityConnections, array $itinerariesInProcess, Itinerary $itinerary): array
    {
        foreach ($lastCityConnections as $lastCityConnection) {
            $newItinerary = clone $itinerary;
            $newItinerary->addStop($lastCityConnection);
            $itinerariesInProcess[] = $newItinerary;
        }

        return $itinerariesInProcess;
    }
}
