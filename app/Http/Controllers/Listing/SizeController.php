<?php

declare(strict_types=1);

namespace App\Http\Controllers\Listing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Listing\Size\UpdateRequest;
use App\Models\Listing;
use App\Service\SizeService;
use App\UseCase\Listing\Size\Update;
use Throwable;

class SizeController extends Controller
{
    /** @var SizeService */
    private SizeService $sizeService;

    /**
     * @param SizeService $sizeService
     */
    public function __construct(SizeService $sizeService)
    {
        $this->sizeService = $sizeService;
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

        $sizes           = $this->sizeService->findAll();
        $listingSizesIds = $this->sizeService->getSizeIdsByListingAsArray($listing);

        return view('listings.sizes.edit', compact('listing', 'sizes', 'listingSizesIds'));
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

        $command = new Update\Command($listing->id, $request->get('sizes'));

        try {
            $handler = new Update\Handler();
            $handler->handle($command);

            return redirect()->route('listings.edit', $listing)->with('alert.success', 'Sizes updated');
        } catch (Throwable $e) {
            return redirect()->back()->with('alert.error', 'Failed to update sizes');
        }
    }
}
