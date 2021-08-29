<?php

namespace App\Http\Controllers\Listing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Listing\Listing\CreateRequest;
use App\Http\Requests\Listing\Listing\UpdateRequest;
use App\Models\Listing;
use App\Service\CategoryService;
use App\Service\ListingService;
use App\UseCase\Listing\Listing\Create;
use App\UseCase\Listing\Listing\Update;
use App\UseCase\Listing\Listing\Delete;
use Illuminate\Http\UploadedFile;
use Throwable;

class ListingController extends Controller
{
    /** @var ListingService */
    private ListingService $listingService;

    /** @var CategoryService */
    private CategoryService $categoryService;

    /**
     * @param ListingService  $listingService
     * @param CategoryService $categoryService
     */
    public function __construct(ListingService $listingService, CategoryService $categoryService)
    {
        $this->listingService  = $listingService;
        $this->categoryService = $categoryService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $listings = $this->listingService->findAll();

        return view('listings.listings.index', compact('listings'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = $this->categoryService->findAll();

        return view('listings.listings.create', compact('categories'));
    }

    /**
     * @param CreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateRequest $request)
    {
        $userId = auth()->id();

        /** @var UploadedFile $photo */
        $photo = $request->file('photo');

        $command = new Create\Command(
            $request->get('title'),
            $request->get('description'),
            (int) $request->get('price'),
            (int) $userId,
            $request->get('categories'),
            $photo
        );

        try {
            $handler = new Create\Handler();
            $handler->handle($command);

            return redirect()->route('listings.index')->with('alert.success', 'Listing created');
        } catch (Throwable $e) {
            return redirect()->back()->with('alert.error', 'Failed to create listing');
        }
    }

    /**
     * @param Listing $listing
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);

        return view('listings.listings.edit', compact('listing'));
    }

    /**
     * @param UpdateRequest $request
     * @param Listing       $listing
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateRequest $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $userId = auth()->id();

        $command = new Update\Command(
            $listing->id,
            $request->get('title'),
            $request->get('description'),
            (int) $request->get('price'),
            (int) $userId
        );

        try {
            $handler = new Update\Handler();
            $handler->handle($command);

            return redirect()->route('listings.index')->with('alert.success', 'Listing updated');
        } catch (Throwable $e) {
            return redirect()->back()->with('alert.error', 'Failed to update listing');
        }
    }

    /**
     * @param Listing $listing
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);

        $userId = auth()->id();

        $command = new Delete\Command($listing->id, (int) $userId);

        try {
            $handler = new Delete\Handler();
            $handler->handle($command);

            return redirect()->route('listings.index')->with('alert.success', 'Listing deleted');
        } catch (Throwable $e) {
            return redirect()->back()->with('alert.error', 'Failed to delete listing');
        }
    }
}
