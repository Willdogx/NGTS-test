<?php

declare(strict_types=1);

namespace Test\Factory;

use App\Factory\TransportCalculationServiceFactory;
use App\Service\TransportCalculationService;
use PHPUnit\Framework\TestCase;

/**
 * @covers TransportCalculationServiceFactory
 */
class TransportCalculationServiceFactoryTest extends TestCase
{
    public function testGetServiceReturnsInstanceOfTransportCalculationService(): void
    {
        $factory = new TransportCalculationServiceFactory();
        $this->assertInstanceOf(TransportCalculationService::class, $factory->getService());
    }
}
