<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\Category;
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
}
