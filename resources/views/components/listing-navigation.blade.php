<div class="mb-5 border-b-2 border-blue-500">
    <ul class='flex'>
        <li class='py-2 px-6 bg-white rounded-t-lg {{ request()->routeIs('listings.edit') ? 'text-blue-500 bg-blue-200' : '' }}'><a href="{{ route('listings.edit', $listing) }}">Common</a></li>
        <li class='py-2 px-6 bg-white rounded-t-lg {{ request()->routeIs('listings.media.show') ? 'text-blue-500 bg-blue-200' : '' }}'><a href="{{ route('listings.media.show', $listing) }}">Media</a></li>
        <li class='py-2 px-6 bg-white rounded-t-lg {{ request()->routeIs('listings.categories.edit') ? 'text-blue-500 bg-blue-200' : '' }}'><a href="{{ route('listings.categories.edit', $listing) }}">Categories</a></li>
        <li class='py-2 px-6 bg-white rounded-t-lg {{ request()->routeIs('listings.colors.edit') ? 'text-blue-500 bg-blue-200' : '' }}'><a href="{{ route('listings.colors.edit', $listing) }}">Colors</a></li>
        <li class='py-2 px-6 bg-white rounded-t-lg {{ request()->routeIs('listings.sizes.edit') ? 'text-blue-500 bg-blue-200' : '' }}'><a href="{{ route('listings.sizes.edit', $listing) }}">Sizes</a></li>
    </ul>
</div>
