<?php

declare(strict_types=1);

namespace App\Model;

use function array_reduce;
use function implode;

class Itinerary
{
    /**
     * @var Connection[]
     */
    private array $stops;

    public function __construct(City $startingPoint)
    {
        $this->stops[] = new Connection($startingPoint->getName(), 0);
    }

    public function __toString(): string
    {
        $toString = [];
        foreach ($this->stops as $connection) {
            $toString[] = sprintf('%s (%s)', $connection->getName(), $connection->getPrice());
        }

        return implode(' -> ', $toString) . ' | TOTAL: ' . $this->getTotalCost();
    }

    public function addStop(Connection $connection): self
    {
        $this->stops[] = $connection;

        return $this;
    }

    /**
     * @return Connection[]
     */
    public function getStops(): array
    {
        return $this->stops;
    }

    public function getTotalCost(): int
    {
        return array_reduce($this->stops, fn (int $carry, Connection $connection) => $carry + $connection->getPrice(), 0);
    }
}
