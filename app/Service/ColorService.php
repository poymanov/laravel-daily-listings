<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\Color;
use App\Models\Listing;
use Illuminate\Database\Eloquent\Collection;

class ColorService
{
    /**
     * Получение списка всех цветов
     *
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Color::all();
    }

    /**
     * Получение id цветов предложения в виде массива
     *
     * @param Listing $listing Предложение, для которого необходимо получить ID цветов
     *
     * @return array
     */
    public function getColorIdsByListingAsArray(Listing $listing): array
    {
        return $listing->colors()->pluck('id')->toArray();
    }
}
