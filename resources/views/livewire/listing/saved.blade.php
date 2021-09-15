<div class="text-sm flex items-start mb-2">
    <input id="saved" class="mr-2 focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" type="checkbox" name="saved" @if($isFilterBySaved) checked @endif />
    <x-jet-label for="saved" value="Saved ({{ $savedCount }})"/>
</div>
