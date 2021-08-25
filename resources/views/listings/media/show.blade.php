<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Media') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-listing-navigation :listing="$listing"/>

                    <x-jet-validation-errors class="mb-4"/>

                    <form method="POST" action="{{ route('listings.media.store', $listing) }}" enctype="multipart/form-data" class="mb-4">
                        @csrf

                        <div class="flex items-center">
                            <div class="w-3/4 mr-2">
                                <x-jet-label for="photos" value="{{ __('New photos') }}"/>
                                <x-jet-input id="photos" class="block mt-1 w-full" type="file" name="photos[]" multiple/>
                            </div>
                            <x-jet-button>
                                {{ __('Add') }}
                            </x-jet-button>
                        </div>
                    </form>

                    <hr class="mb-4">

                    <div class="mb-4 flex flex-wrap">
                        @foreach($listing->getMedia('listings') as $media)
                            <a href="{{ $media->getUrl() }}" target="_blank" class="block mr-2 mb-2"><img src="{{ $media->getUrl('thumb') }}"></a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
