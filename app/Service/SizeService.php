<?php

declare(strict_types=1);

namespace App\Service;

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
}
