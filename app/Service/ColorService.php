<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\Color;
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
}
