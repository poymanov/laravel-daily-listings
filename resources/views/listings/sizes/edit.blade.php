<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sizes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-listing-navigation :listing="$listing"/>

                    <x-jet-validation-errors class="mb-4"/>

                    <form method="POST" action="{{ route('listings.sizes.update', $listing) }}" class="mb-4">
                        @csrf

                        <div class="mb-4">
                            @foreach($sizes as $size)
                                <div class="text-sm flex items-start mb-2">
                                    <input id="size-{{ $size->id }}" class="mr-2 focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" @if (in_array($size->id, $listingSizesIds)) checked @endif type="checkbox" name="sizes[]" value="{{ $size->id }}"/>
                                    <x-jet-label for="size-{{ $size->id }}" value="{{ $size->name }}"/>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center">
                            <x-jet-button>
                                {{ __('Update') }}
                            </x-jet-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
