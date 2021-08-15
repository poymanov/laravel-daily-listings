<?php

namespace App\Http\Controllers;

use App\Service\ListingService;

class ListingController extends Controller
{
    /** @var ListingService */
    private ListingService $listingService;

    /**
     * @param ListingService $listingService
     */
    public function __construct(ListingService $listingService)
    {
        $this->listingService = $listingService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $listings = $this->listingService->findAll();

        return view('listing.index', compact('listings'));
    }
}
