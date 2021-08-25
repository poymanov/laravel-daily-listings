<?php

declare(strict_types=1);

namespace App\Http\Controllers\Listing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Listing\Media\StoreRequest;
use App\Models\Listing;
use App\UseCase\Listing\Media\Store;
use Throwable;

class MediaController extends Controller
{
    /**
     * @param Listing $listing
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Listing $listing)
    {
        $this->authorize('update', $listing);

        return view('listings.media.show', compact('listing'));
    }

    /**
     * @param StoreRequest $request
     * @param Listing      $listing
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreRequest $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $userId = (int) auth()->id();
        $photos = $request->allFiles()['photos'];

        $command = new Store\Command($listing->id, $userId, $photos);

        try {
            $handler = new Store\Handler();
            $handler->handle($command);

            return redirect()->route('listings.edit', $listing)->with('alert.success', 'Media added');
        } catch (Throwable $e) {
            return redirect()->back()->with('alert.error', 'Failed to add media');
        }
    }
}
