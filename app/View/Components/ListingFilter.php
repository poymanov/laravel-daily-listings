<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Service\CategoryService;
use App\Service\CityService;
use App\Service\ColorService;
use App\Service\SizeService;
use Illuminate\Support\Facades\App;
use Illuminate\View\Component;

class ListingFilter extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $categoryService = App::make(CategoryService::class);
        $colorService    = App::make(ColorService::class);
        $sizeService     = App::make(SizeService::class);
        $cityService     = App::make(CityService::class);

        $categories = $categoryService->findAll();
        $colors     = $colorService->findAll();
        $sizes      = $sizeService->findAll();
        $cities     = $cityService->findAll();

        $currentTitle      = request('title');
        $currentCategoryId = request('category');
        $currentColorId    = request('color');
        $currentSizeId     = request('size');
        $currentCityId     = request('city');
        $currentSaved      = request('saved');

        return view(
            'components.listing-filter',
            compact(
                'categories',
                'colors',
                'sizes',
                'cities',
                'currentTitle',
                'currentCategoryId',
                'currentColorId',
                'currentSizeId',
                'currentCityId',
                'currentSaved'
            )
        );
    }
}
