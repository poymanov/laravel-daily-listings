<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Listing;
use Illuminate\View\Component;

class ListingNavigation extends Component
{
    /** @var Listing */
    public Listing $listing;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Listing $listing)
    {
        $this->listing = $listing;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.listing-navigation', ['listing' => $this->listing]);
    }
}
