<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Collection;

class ListingService
{
    /**
     * Получение списка всех предложений
     *
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Listing::all();
    }
}
