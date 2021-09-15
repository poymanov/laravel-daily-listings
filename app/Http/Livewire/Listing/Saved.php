<?php

declare(strict_types=1);

namespace App\Http\Livewire\Listing;

use App\Models\User;
use Livewire\Component;

class Saved extends Component
{
    /** @var bool */
    public $isFilterBySaved;

    /** @var string[] */
    protected $listeners = [
        'savedListingsUpdate' => 'render',
    ];

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.listing.saved', [
            'savedCount'      => $this->getSavedListingsCount(),
            'isFilterBySaved' => $this->isFilterBySaved,
        ]);
    }

    /**
     * Получение количества сохраненных предложений пользователя
     *
     * @return int
     */
    private function getSavedListingsCount(): int
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->savedListings()->count();
    }
}
