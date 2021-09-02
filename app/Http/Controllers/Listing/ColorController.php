<?php

declare(strict_types=1);

namespace App\Http\Controllers\Listing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Listing\Color\UpdateRequest;
use App\Models\Listing;
use App\Service\ColorService;
use App\UseCase\Listing\Color\Update;
use Throwable;

class ColorController extends Controller
{
    /** @var ColorService */
    private ColorService $colorService;

    /**
     * @param ColorService $colorService
     */
    public function __construct(ColorService $colorService)
    {
        $this->colorService = $colorService;
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

        $colors           = $this->colorService->findAll();
        $listingColorsIds = $this->colorService->getColorIdsByListingAsArray($listing);

        return view('listings.colors.edit', compact('listing', 'colors', 'listingColorsIds'));
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

        $command = new Update\Command($listing->id, $request->get('colors'));

        try {
            $handler = new Update\Handler();
            $handler->handle($command);

            return redirect()->route('listings.edit', $listing)->with('alert.success', 'Colors updated');
        } catch (Throwable $e) {
            return redirect()->back()->with('alert.error', 'Failed to update colors');
        }
    }
}
