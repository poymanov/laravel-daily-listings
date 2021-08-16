<?php

namespace App\Http\Controllers;

use App\Http\Requests\Requests\Listing\CreateRequest;
use App\Service\ListingService;
use App\UseCase\Listing\Create;
use Throwable;

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

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('listing.create');
    }

    /**
     * @param CreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateRequest $request)
    {
        $userId = auth()->id();

        $command = new Create\Command(
            $request->get('title'),
            $request->get('description'),
            (int) $request->get('price'),
            (int) $userId,
        );

        try {
            $handler = new Create\Handler();
            $handler->handle($command);

            return redirect()->route('listing.index')->with('alert.success', 'Listing created');
        } catch (Throwable $e) {
            return redirect()->back()->with('alert.error', 'Failed to create listing');
        }
    }
}
