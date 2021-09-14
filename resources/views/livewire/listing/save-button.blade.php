<div>
    @if($isListingSaved)
        <a href="#" wire:click.prevent="unSave" class="text-indigo-600 hover:text-indigo-900 mr-2">Remove from saved</a>
    @else
        <a href="#" wire:click.prevent="save" class="text-indigo-600 hover:text-indigo-900 mr-2">Add to saved</a>
    @endif
</div>
