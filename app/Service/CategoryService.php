<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\Category;
use App\Models\Listing;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    /**
     * Получение списка всех категорий
     *
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Category::all();
    }

    /**
     * Получение id категорий предложения в виде массива
     *
     * @param Listing $listing Предложение, для которого необходимо получить ID категорий
     *
     * @return array
     */
    public function getCategoryIdsByListingAsArray(Listing $listing): array
    {
        return $listing->categories()->pluck('id')->toArray();
    }
}
