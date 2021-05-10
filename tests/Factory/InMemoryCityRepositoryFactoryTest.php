<?php

declare(strict_types=1);

namespace Test\Factory;

use App\Data\Repository\InMemoryCityRepository;
use App\Factory\InMemoryCityRepositoryFactory;
use App\Factory\TransportCalculationServiceFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers InMemoryCityRepositoryFactory
 */
class InMemoryCityRepositoryFactoryTest extends TestCase
{
    public function testGetRepositoryReturnsInstanceOfInMemoryCityRepository(): void
    {
        $factory = new InMemoryCityRepositoryFactory();
        $this->assertInstanceOf(InMemoryCityRepository::class, $factory->getRepository());
    }
}
