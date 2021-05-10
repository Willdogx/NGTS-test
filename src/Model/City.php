<?php

declare(strict_types=1);

namespace App\Model;

class City
{
    private string $name;
    /**
     * @var Connection[]
     */
    private array $connections;

    /**
     * @param Connection[] $connections
     */
    public function __construct(string $name, array $connections)
    {
        $this->name = $name;
        $this->connections = $connections;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Connection[]
     */
    public function getConnections(): array
    {
        return $this->connections;
    }
}
