<div {!! $attributes !!}>
    @if ($status === 'success')
        <div class="bg-green-500">
            @include('components.alert.message', compact('message'))
        </div>
    @elseif ($status === 'error')
        <div class="bg-red-500">
            @include('components.alert.message', compact('message'))
        </div>
    @endif
</div>
