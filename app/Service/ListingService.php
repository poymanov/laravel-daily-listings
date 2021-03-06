<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ListingService
{
    /**
     * Получение предложения по ID
     *
     * @param int $id ID предложения, которое необходимо получить
     *
     * @return Listing|null
     */
    public function findById(int $id): ?Listing
    {
        return Listing::whereId($id)->first();
    }

    /**
     * Получение списка всех предложений
     *
     * @return Collection
     */
    public function findAll(): Collection
    {
        return Listing::with(['categories', 'colors', 'sizes'])->get();
    }

    /**
     * Получение отфильтрованного списка всех предложений
     *
     * @param string|null $title         Заголовок для фильтрации
     * @param int|null    $categoryId    ID категории для фильтрации
     * @param int|null    $colorId       ID цвета для фильтрации
     * @param int|null    $sizeId        ID размера для фильтрации
     * @param int|null    $cityId        ID города для фильтрации
     * @param int|null    $savedByUserId ID пользователя, который сохранил предложения, для фильтрации
     *
     * @return LengthAwarePaginator
     */
    public function filtered(?string $title, ?int $categoryId, ?int $colorId, ?int $sizeId, ?int $cityId, ?int $savedByUserId): LengthAwarePaginator
    {
        return Listing::with(['categories', 'colors', 'sizes', 'user.city'])
            ->when($title, function ($query) use ($title) {
                $query->where('title', 'LIKE', '%' . $title . '%');
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->whereHas('categories', function ($queryRelation) use ($categoryId) {
                    $queryRelation->where('id', $categoryId);
                });
            })
            ->when($colorId, function ($query) use ($colorId) {
                $query->whereHas('colors', function ($queryRelation) use ($colorId) {
                    $queryRelation->where('id', $colorId);
                });
            })
            ->when($sizeId, function ($query) use ($sizeId) {
                $query->whereHas('sizes', function ($queryRelation) use ($sizeId) {
                    $queryRelation->where('id', $sizeId);
                });
            })
            ->when($cityId, function ($query) use ($cityId) {
                $query->whereHas('user.city', function ($queryRelation) use ($cityId) {
                    $queryRelation->where('id', $cityId);
                });
            })
            ->when($savedByUserId, function ($query) use ($savedByUserId) {
                $query->whereHas('savedUsers', function ($queryRelation) use ($savedByUserId) {
                    $queryRelation->where('id', $savedByUserId);
                });
            })
            ->paginate(config('pagination.listings'))->withQueryString();
    }
}
