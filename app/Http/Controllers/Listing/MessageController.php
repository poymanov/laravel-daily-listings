<?php

declare(strict_types=1);

namespace App\Http\Controllers\Listing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Listing\Message\StoreRequest;
use App\Models\Listing;
use App\UseCase\Listing\Message\Store;
use Throwable;

class MessageController extends Controller
{
    /**
     * @param Listing $listing
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Listing $listing)
    {
        $this->authorize('sendMessage', $listing);

        return view('listings.messages.create', compact('listing'));
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
        $this->authorize('sendMessage', $listing);

        $command = new Store\Command(
            (int) auth()->id(),
            $listing->id,
            $request->get('text'),
        );

        try {
            $handler = new Store\Handler();
            $handler->handle($command);

            return redirect()->route('listings.index')->with('alert.success', 'Message sent successfully');
        } catch (Throwable $e) {
            return redirect()->back()->with('alert.error', 'Failed to create listing');
        }
    }
}
