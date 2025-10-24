<div>
    <div class="pt-6 px-4 xl:pl-10 xl:pr-0 max-w-7xl mx-auto mb-4 flex flex-col">
        <div class="overflow-x-auto">
            <div class="align-middle inline-block min-w-full">
                <div class="shadow overflow-hidden">
                    <div class="relative w-full h-full md:h-auto">

                        @if (session('success'))
                            <div class="my-4 bg-green-100 px-6 py-5 text-base text-green-700" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="bg-white rounded-lg relative">

                            <div class="flex items-start p-5 border-b rounded-t">
                                <div class="flex items-center">
                                    <h3 class="text-2xl font-bold leading-none text-gray-900">
                                        {{ $offer->name }}</h3>
                                </div>
                            </div>

                            <form method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.offer.update', ['offer' => $offer->id]) }}">
                                @csrf
                                @method('PUT')
                                <input name="image_remove" type="text" id="image_remove" class="hidden"
                                    style="z-index:-10;" />
                                <input name="image_remove_1" type="text" id="image_remove_1" class="hidden"
                                    style="z-index:-10;" />
                                <input name="image_remove_2" type="text" id="image_remove_2" class="hidden"
                                    style="z-index:-10;" />
                                <input name="image_remove_3" type="text" id="image_remove_3" class="hidden"
                                    style="z-index:-10;" />
                                <input name="image_remove_4" type="text" id="image_remove_4" class="hidden"
                                    style="z-index:-10;" />


                                @php
                                    $images = $offer->images()->get();
                                @endphp

                                <div class="flex flex-row border-b" wire:ignore>

                                    <!-- image  -->
                                    <div class="flex flex-row" id="upload_area">

                                        <div class="flex relative p-3">
                                            <img class="h-20 w-20 rounded-lg m-4 object-cover" id="img"
                                                alt="image"
                                                @if (empty($offer->image)) src="{{ url('/image/no-image.png') }}" @else src="{{ \App\Helpers\StorageHelper::imageUrl($offer->image) }}" @endif>

                                            <button type="button" id="remove_image" class="absolute top-2 right-2"
                                                @if (isset($offer->image) && empty($images[0])) style="display: block;" @else style="display: none;" @endif>

                                                <img src="{{ url('/image/remove.png') }}" class="w-5 h-5"
                                                    style="cursor:pointer;">
                                            </button>
                                        </div>

                                        <div class="items-center" id="title_image"
                                            @if (empty($offer->image)) style="display: flex;" @else style="display: none;" @endif>

                                            <label class="input-file relative inline-block">
                                                <input name="image" type="file" accept=".jpg,.jpeg,.png"
                                                    id="image" class="absolute opacity-0 block w-0 h-0"
                                                    style="z-index:-1;" />
                                                <span id="image_span"
                                                    class="relative inline-block align-middle text-center p-2 rounded-lg w-full text-slate-600"
                                                    style="cursor:pointer;">Выберите файл или перетащите сюда</span>
                                            </label>
                                        </div>

                                    </div>

                                    <!-- image 1 -->
                                    <div class="flex flex-row" id="upload_area_1"
                                        @if (empty($offer->image)) style="display: none;" @else style="display: flex;" @endif>

                                        <div class="flex relative p-3">
                                            <img class="h-20 w-20 rounded-lg m-4 object-cover" id="img_1"
                                                alt="image"
                                                @if (empty($images[0])) src="{{ url('/image/no-image.png') }}" @else src="{{ \App\Helpers\StorageHelper::imageUrl($images[0]->path) }}" @endif>

                                            <button type="button" id="remove_image_1" class="absolute top-2 right-2"
                                                @if (isset($images[0]) && empty($images[1])) style="display: block;" @else style="display: none;" @endif>

                                                <img src="{{ url('/image/remove.png') }}" class="w-5 h-5"
                                                    style="cursor:pointer;">
                                            </button>
                                        </div>

                                        <div class="items-center" id="title_image_1"
                                            @if (empty($images[0])) style="display: flex;" @else style="display: none;" @endif>

                                            <label class="input-file relative inline-block">
                                                <input name="image_1" type="file" accept=".jpg,.jpeg,.png"
                                                    id="image_1" class="absolute opacity-0 block w-0 h-0"
                                                    style="z-index:-1;" />
                                                <span id="image_span_1"
                                                    class="relative inline-block align-middle text-center p-2 rounded-lg w-full text-slate-600"
                                                    style="cursor:pointer;">Выберите файл или перетащите сюда</span>
                                            </label>
                                        </div>

                                    </div>

                                    <!-- image 2 -->
                                    <div class="flex-row" id="upload_area_2"
                                        @if (empty($images[0])) style="display: none;" @else style="display: flex;" @endif>

                                        <div class="flex relative p-3">

                                            <img class="h-20 w-20 rounded-lg m-4 object-cover" id="img_2"
                                                alt="image"
                                                @if (empty($images[1])) src="{{ url('/image/no-image.png') }}" @else src="{{ \App\Helpers\StorageHelper::imageUrl($images[1]->path) }}" @endif>

                                            <button type="button" id="remove_image_2" class="absolute top-2 right-2"
                                                @if (isset($images[1]) && empty($images[2])) style="display: block;" @else style="display: none;" @endif>

                                                <img src="{{ url('/image/remove.png') }}" class="w-5 h-5"
                                                    style="cursor:pointer;">
                                            </button>
                                        </div>

                                        <div class="items-center" id="title_image_2"
                                            @if (empty($images[1])) style="display: flex;" @else style="display: none;" @endif>

                                            <label class="input-file relative inline-block">
                                                <input name="image_2" type="file" accept=".jpg,.jpeg,.png"
                                                    id="image_2" class="absolute opacity-0 block w-0 h-0"
                                                    style="z-index:-1;" />
                                                <span id="image_span_2"
                                                    class="relative inline-block align-middle text-center p-2 rounded-lg w-full text-slate-600"
                                                    style="cursor:pointer;">Выберите файл или перетащите сюда</span>
                                            </label>
                                        </div>
                                    </div>


                                    <!-- image 3 -->
                                    <div class="flex-row" id="upload_area_3"
                                        @if (empty($images[1])) style="display: none;" @else style="display: flex;" @endif>
                                        <div class="flex relative p-3">

                                            <img class="h-20 w-20 rounded-lg m-4 object-cover" id="img_3"
                                                alt="image"
                                                @if (empty($images[2])) src="{{ url('/image/no-image.png') }}" @else src="{{ asset('storage/' . $images[2]->path) }}" @endif>

                                            <button type="button" id="remove_image_3" class="absolute top-2 right-2"
                                                @if (isset($images[2]) && empty($images[3])) style="display: block;" @else style="display: none;" @endif>

                                                <img src="{{ url('/image/remove.png') }}" class="w-5 h-5"
                                                    style="cursor:pointer;">
                                            </button>
                                        </div>

                                        <div class="items-center" id="title_image_3"
                                            @if (empty($images[2])) style="display: flex;" @else style="display: none;" @endif>

                                            <label class="input-file relative inline-block">
                                                <input name="image_3" type="file" accept=".jpg,.jpeg,.png"
                                                    id="image_3" class="absolute opacity-0 block w-0 h-0"
                                                    style="z-index:-1;" />
                                                <span id="image_span_3"
                                                    class="relative inline-block align-middle text-center p-2 rounded-lg w-full text-slate-600"
                                                    style="cursor:pointer;">Выберите файл или перетащите сюда</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- image 4 -->
                                    <div class="flex-row" id="upload_area_4"
                                        @if (empty($images[2])) style="display: none;" @else style="display: flex;" @endif>

                                        <div class="flex relative p-3">

                                            <img class="h-20 w-20 rounded-lg m-4 object-cover" id="img_4"
                                                alt="image"
                                                @if (empty($images[3])) src="{{ url('/image/no-image.png') }}" @else src="{{ asset('storage/' . $images[3]->path) }}" @endif>

                                            <button type="button" id="remove_image_4" class="absolute top-2 right-2"
                                                @if (isset($images[3])) style="display: block;" @else style="display: none;" @endif>

                                                <img src="{{ url('/image/remove.png') }}" class="w-5 h-5"
                                                    style="cursor:pointer;">
                                            </button>
                                        </div>

                                        <div class="items-center" id="title_image_4"
                                            @if (empty($images[3])) style="display: flex;" @else style="display: none;" @endif>

                                            <label class="input-file relative inline-block">
                                                <input name="image_4" type="file" accept=".jpg,.jpeg,.png"
                                                    id="image_4" class="absolute opacity-0 block w-0 h-0"
                                                    style="z-index:-1;" />
                                                <span id="image_span_4"
                                                    class="relative inline-block align-middle text-center p-2 rounded-lg w-full text-slate-600"
                                                    style="cursor:pointer;">Выберите файл или перетащите сюда</span>
                                            </label>
                                        </div>
                                    </div>


                                </div>

                                <div>
                                    <x-input-error :messages="$errors->get('image')" />
                                </div>

                                <div class="p-6 space-y-3">
                                    <div class="grid grid-cols-6 gap-4">

                                        {{-- Название --}}
                                        <div class="col-span-6 md:col-span-2">
                                            <label for="name"
                                                class="text-sm font-medium text-gray-900 block mb-2">Название *</label>
                                            <input type="text" name="name" id="name"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                value="{{ old('name', $offer->name) }}" required autofocus>
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>

                                        {{-- Телефон --}}
                                        <div class="col-span-6 md:col-span-2">
                                            <label for="phone"
                                                class="text-sm font-medium text-gray-900 block mb-2">Телефон</label>
                                            <input type="tel" name="phone" id="phone" wire:ignore
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 mask-phone"
                                                value="{{ old('phone', $offer->phone) }}">
                                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                        </div>

                                        {{-- Город --}}
                                        <div class="col-span-6 md:col-span-2" id="city_div" wire:ignore>
                                            <x-admin.select-city :selectedCity="$offer->city" />
                                        </div>

                                        {{-- Сущность --}}
                                        <x-admin.select-entity :selectedEntity="$offer->entity" />

                                        {{-- Категории --}}
                                        @if (count($categories) > 0)
                                            <div class="col-span-6">
                                                <label for="categories"
                                                    class="text-sm font-medium text-gray-900 block mb-2">Направление</label>
                                                <div class="flex border-2 rounded-lg p-4  mt-1" id="checkbox-group">
                                                    <div class="grid grid-cols-3 gap-4 w-full">

                                                        @foreach ($categories as $item)
                                                            <div class="flex flex-col gap-1">
                                                                <div class="flex">
                                                                    @if (count($item->categories) < 1)
                                                                        <input type="radio" name="category"
                                                                            value="{{ $item->id }}"
                                                                            @checked($offer->category_id == $item->id)
                                                                            @if (is_array(old('category')) && in_array($item->id, old('category'))) checked @endif
                                                                            class="checkbox-{{ $loop->iteration }} shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                                                            id="checkbox-{{ $item->id }}">
                                                                        <label for="checkbox-{{ $item->id }}"
                                                                            class="text-sm text-gray-500 ms-3">{{ $item->name }}</label>
                                                                    @else
                                                                        <label
                                                                            for="checkbox-group-{{ $loop->iteration }}"
                                                                            class="text-base text-black ms-3">{{ $item->name }}</label>
                                                                    @endif
                                                                </div>
                                                                @foreach ($item->categories as $child)
                                                                    <div class="flex">
                                                                        <input type="radio" name="category"
                                                                            @checked($offer->category_id == $child->id)
                                                                            value="{{ $child->id }}" required
                                                                            class="checkbox-{{ $loop->parent->iteration }} shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                                                            id="checkbox-{{ $child->id }}">
                                                                        <label for="checkbox-{{ $child->id }}"
                                                                            class="text-sm text-gray-500 ms-3">{{ $child->name }}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                    <x-input-error class="mt-2" :messages="$errors->get('categories')" />
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Адрес --}}
                                        <div class="col-span-6">
                                            <label for="address"
                                                class="text-sm font-medium text-gray-900 block mb-2">Адрес</label>
                                            <input type="text" name="address" id="address"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                value="{{ old('address', $offer->address) }}">
                                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                        </div>

                                        {{-- Описание --}}
                                        <div class="col-span-6">
                                            <label for="description"
                                                class="text-sm font-medium text-gray-900 block mb-2">Описание</label>
                                            <textarea type="text" name="description" id="description"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">{{ old('description', $offer->description) }}</textarea>
                                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                        </div>

                                    </div>

                                    <hr class="my-5">
                                    <div class="items-center pb-6 border-gray-200 rounded-b">
                                        <div class="col-span-6">
                                            <div class="flex w-full justify-between">
                                                <label for="activity" class="inline-flex items-center">
                                                    <input id="activity" type="checkbox" @checked($offer->activity)
                                                        value="1"
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                        name="activity">
                                                    <span class="ml-2 text-sm text-gray-600">Активность</span>
                                                </label>
                                                <button
                                                    class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                                    type="submit">ОБНОВИТЬ</button>
                                            </div>

                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {

            const maxSize = 2000000; // 2 MB

            function updatePreview(input, imgSelector, spanSelector, sectionSelector, removeBtnSelectorShow,
                removeBtnSelectorHide, nextSectionSelector) {
                const file = input.files[0];
                if (file.size > maxSize) {
                    $(spanSelector).html('Максимальный размер 2 МБ').css("color", "rgb(239 68 68)");
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    $(imgSelector).attr('src', e.target.result);
                };
                reader.readAsDataURL(file);

                $(spanSelector).html(file.name).css("color", "rgb(71 85 105)");
                $(sectionSelector).css("display", "none");
                $(removeBtnSelectorHide).css("display", "none");
                $(removeBtnSelectorShow).css("display", "block");
                $(nextSectionSelector).css({
                    "display": "flex",
                    "flex-direction": "row"
                });
            }

            function removeFile(inputSelector, imgSelector, spanSelector, sectionSelector,
                removeBtnSelectorShow, removeBtnSelectorHide, prevSectionSelector, imageDelete) {
                $(inputSelector).val('');
                $(imgSelector).attr('src', `{{ url('/image/no-image.png') }}`);
                $(spanSelector).html('Выберите файл или перетащите сюда').css("color", "rgb(71 85 105)");
                $(sectionSelector).css("display", "none");
                $(removeBtnSelectorHide).css("display", "none");
                $(imageDelete).val('delete');
                if (removeBtnSelectorShow) {
                    $(removeBtnSelectorShow).css("display", "block");
                }

                if (prevSectionSelector) {
                    $(prevSectionSelector).css({
                        "display": "flex",
                        "flex-direction": "row"
                    });
                }
            }

            // image
            $('#image').on('change', function() {
                updatePreview(this, '#img', '#image_span', '#title_image', '#remove_image', null,
                    '#upload_area_1');
            });

            $('#remove_image').on('click', function() {
                removeFile('#image', '#img', '#image_span', '#upload_area_1', null, '#remove_image',
                    '#upload_area, #title_image', '#image_remove');
            });

            // image 1
            $('#image_1').on('change', function() {
                updatePreview(this, '#img_1', '#image_span_1', '#title_image_1', '#remove_image_1',
                    '#remove_image', '#upload_area_2');
            });
            $('#remove_image_1').on('click', function() {
                removeFile('#image_1', '#img_1', '#image_span_1', '#upload_area_2', '#remove_image',
                    '#remove_image_1', '#upload_area_1, #title_image_1', '#image_remove_1');
            });

            // image 2
            $('#image_2').on('change', function() {
                updatePreview(this, '#img_2', '#image_span_2', '#title_image_2', '#remove_image_2',
                    '#remove_image_1', '#upload_area_3');
            });
            $('#remove_image_2').on('click', function() {
                removeFile('#image_2', '#img_2', '#image_span_2', '#upload_area_3', '#remove_image_1',
                    '#remove_image_2', '#upload_area_2, #title_image_2', '#image_remove_2');
            });

            // image 3
            $('#image_3').on('change', function() {
                updatePreview(this, '#img_3', '#image_span_3', '#title_image_3', '#remove_image_3',
                    '#remove_image_2', '#upload_area_4');
            });
            $('#remove_image_3').on('click', function() {
                removeFile('#image_3', '#img_3', '#image_span_3', '#upload_area_4', '#remove_image_2',
                    '#remove_image_3', '#upload_area_3, #title_image_3', '#image_remove_3');
            });

            // image 4
            $('#image_4').on('change', function() {
                updatePreview(this, '#img_4', '#image_span_4', '#title_image_4', '#remove_image_4',
                    '#remove_image_3', null);
            });
            $('#remove_image_4').on('click', function() {
                removeFile('#image_4', '#img_4', '#image_span_4', null, '#remove_image_3',
                    '#remove_image_4', '#upload_area_4, #title_image_4', '#image_remove_4');
            });


            ['#upload_area', '#upload_area_1', '#upload_area_2', '#upload_area_3', '#upload_area_4'].forEach(
                function(sectionId) {
                    const dropArea = $(sectionId);

                    dropArea.on('dragover', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $(this).addClass('bg-slate-100');
                    });

                    dropArea.on('dragleave', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $(this).removeClass('bg-slate-100');
                    });

                    dropArea.on('drop', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $(this).removeClass('bg-slate-100');

                        const files = e.originalEvent.dataTransfer.files;
                        if (files.length > 0) {
                            const currentInput = $(this).find('input[type="file"]:visible').get(0);
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(files[0]);
                            currentInput.files = dataTransfer.files;
                            $(currentInput).trigger('change');
                        }
                    });
                });
        });
    </script>
    @vite(['resources/js/mask_phone.js'])
</div>
