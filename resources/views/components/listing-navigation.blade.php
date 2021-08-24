<div class="mb-5 border-b-2 border-blue-500">
    <ul class='flex'>
        <li class='py-2 px-6 bg-white rounded-t-lg {{ request()->routeIs('listings.edit') ? 'text-blue-500 bg-blue-200' : '' }}'><a href="{{ route('listings.edit', $listing) }}">Common</a></li>
        <li class='py-2 px-6 bg-white rounded-t-lg {{ request()->routeIs('listings.media.show') ? 'text-blue-500 bg-blue-200' : '' }}'><a href="{{ route('listings.media.show', $listing) }}">Media</a></li>
    </ul>
</div>
