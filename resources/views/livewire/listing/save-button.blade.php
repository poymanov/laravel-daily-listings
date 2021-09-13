<div>
    @if($isListingSaved)
        <a href="#" wire:click.prevent="unSave" class="text-indigo-600 hover:text-indigo-900 mr-2">UnSave</a>
    @else
        <a href="#" wire:click.prevent="save" class="text-indigo-600 hover:text-indigo-900 mr-2">Save</a>
    @endif
</div>
