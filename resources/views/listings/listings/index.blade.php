<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Listings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-5 sm:rounded-lg">
                <a href="{{ route('listings.create') }}" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</a>
            </div>

            <div class="mb-5 bg-white sm:rounded-lg p-5">
                <x-listing-filter/>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-4">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Image
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Title
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Description
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            City
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Price
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Categories
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Colors
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Sizes
                                        </th>
                                        <th scope="col" class="relative px-6 py-3"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($listings as $listing)
                                        <tr>
                                            <td>
                                                @if($listing->hasMedia('listings'))
                                                    <img src="{{ $listing->getFirstMediaUrl('listings', 'thumb') }}"/>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $listing->title }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $listing->description }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $listing->user->city->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $listing->price }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                <ul class="list-disc">
                                                    @foreach($listing->categories as $category)
                                                        <li>{{ $category->name }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                <ul class="list-disc">
                                                    @foreach($listing->colors as $color)
                                                        <li>{{ $color->name }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                <ul class="list-disc">
                                                    @foreach($listing->sizes as $size)
                                                        <li>{{ $size->name }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end items-center">
                                                @can('update', $listing)
                                                    <a href="{{ route('listings.edit', $listing) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                                @endcan

                                                @can('delete', $listing)
                                                    <form action="{{ route('listings.destroy', $listing) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button onclick="return confirm('Are you sure?')" class="text-indigo-600 hover:text-indigo-900">Delete</button>
                                                    </form>
                                                @endcan

                                                @if($listing->user_id != auth()->id())
                                                    @livewire('listing.save-button', ['listingId' => $listing->id])
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4">
                    {{ $listings->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
