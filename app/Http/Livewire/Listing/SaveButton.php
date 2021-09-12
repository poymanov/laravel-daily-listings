<?php

declare(strict_types=1);

namespace App\Http\Livewire\Listing;

use App\Models\User;
use App\Service\ListingService;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class SaveButton extends Component
{
    /** @var int */
    public $listingId;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.listing.save-button');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function save()
    {
        /** @var ListingService $listingService */
        $listingService = App::make(ListingService::class);

        $listing = $listingService->findById($this->listingId);

        if (is_null($listing)) {
            session()->flash('error', 'Listing not found');

            return redirect()->to('/listings');
        }

        $currentUser = $this->getCurrentUser();

        if ($listing->user_id == $currentUser->id) {
            session()->flash('error', 'Failed to save yourself listing');

            return redirect()->to('/listings');
        }

        if ($this->isListingSaved()) {
            session()->flash('error', 'Failed to save already saved listing');

            return redirect()->to('/listings');
        }

        $currentUser->savedListings()->attach($this->listingId);
    }

    /**
     * Получение текущего авторизованного пользователя
     *
     * @return User
     */
    private function getCurrentUser(): User
    {
        /** @var User $user */
        $user = auth()->user();

        return $user;
    }

    /**
     * Проверка, сохранено ли предложение для пользователя
     *
     * @return bool
     */
    private function isListingSaved(): bool
    {
        $currentUser = $this->getCurrentUser();

        return $currentUser->savedListings->contains($this->listingId);
    }
}
