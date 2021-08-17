<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Listing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-jet-validation-errors class="mb-4"/>

                    <form method="POST" action="{{ route('listing.update', $listing) }}">
                        @csrf
                        @method('patch')

                        <div class="mb-4">
                            <x-jet-label for="title" value="{{ __('Title') }}"/>
                            <x-jet-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $listing->title)" required/>
                        </div>

                        <div class="mb-4">
                            <x-jet-label for="description" value="{{ __('Description') }}"/>
                            <textarea id="description" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full" name="description" required>{{ old('description', $listing->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <x-jet-label for="price" value="{{ __('Price') }}"/>
                            <x-jet-input id="price" class="block mt-1 w-full" type="text" name="price" :value="old('price', $listing->price)" required/>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-jet-button class="ml-4">
                                {{ __('Update') }}
                            </x-jet-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
