<form method="get">
    <div class="flex mb-4">
        <div class="mr-2">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" name="title" class="mt-1 block w-full py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $currentTitle }}"/>
        </div>
        <div class="mr-2">
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select id="category" name="category" class="mt-1 block w-full py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">-- choose category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if($category->id == $currentCategoryId) selected @endif>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mr-2">
            <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
            <select id="color" name="color" class="mt-1 block w-full py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">-- choose color --</option>
                @foreach($colors as $color)
                    <option value="{{ $color->id }}" @if($color->id == $currentColorId) selected @endif>{{ $color->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mr-2">
            <label for="size" class="block text-sm font-medium text-gray-700">Size</label>
            <select id="size" name="size" class="mt-1 block w-full py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">-- choose size --</option>
                @foreach($sizes as $size)
                    <option value="{{ $size->id }}" @if($size->id == $currentSizeId) selected @endif>{{ $size->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mr-2">
            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
            <select id="city" name="city" class="mt-1 block w-full py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">-- choose city --</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" @if($city->id == $currentCityId) selected @endif>{{ $city->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
            Search
        </button>
    </div>
</form>
