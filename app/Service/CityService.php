<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;

class CityService
{
    /**
     * Получение списка всех городов
     *
     * @return Collection
     */
    public function findAll(): Collection
    {
        return City::all();
    }
}
