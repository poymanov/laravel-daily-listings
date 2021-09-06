<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\Listing;
use App\Models\Size;
use Illuminate\Database\Eloquent\Collection;

class SizeService
{
    /**
     * Получение списка всех размеров
     *
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Size::all();
    }

    /**
     * Получение id размеров предложения в виде массива
     *
     * @param Listing $listing Предложение, для которого необходимо получить ID размеров
     *
     * @return array
     */
    public function getSizeIdsByListingAsArray(Listing $listing): array
    {
        return $listing->sizes()->pluck('id')->toArray();
    }
}
