<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo/>
        </x-slot>

        <x-jet-validation-errors class="mb-4"/>

        <form method="POST" action="{{ route('registration-step-two.update') }}">
            @csrf
            @method('patch')

            <div class="mb-4">
                <x-jet-label for="city_id" value="{{ __('City') }}"/>
                <select name="city_id" required id="city_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" autofocus>
                    <option value=""></option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <x-jet-label for="address" value="{{ __('Address') }}"/>
                <x-jet-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required/>
            </div>

            <div class="mb-4">
                <x-jet-label for="phone" value="{{ __('Phone') }}"/>
                <x-jet-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autocomplete="phone"/>
            </div>

            <div class="flex items-center justify-end">
                <x-jet-button class="ml-4">
                    {{ __('Finish') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
