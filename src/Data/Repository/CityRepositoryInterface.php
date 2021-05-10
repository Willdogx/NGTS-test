<?php

declare(strict_types=1);

namespace App\Data\Repository;

use App\Model\City;

interface CityRepositoryInterface
{
    /**
     * @return City[]
     */
    public function getCities(): array;
}
