<div class="mt-4">
    <div class="bg-white mt-3 basis-full rounded-md block ">
        <select name="city" required
            class="w-full border-1 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
            autocomplete="off">
            <option value="">Выберите город</option>
            @foreach ($cities as $city)
                <option value='{{ $city->id }}' @selected(old('city') == $city->id)>{{ $city->name }}</option>
            @endforeach
        </select>
    </div>
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>
