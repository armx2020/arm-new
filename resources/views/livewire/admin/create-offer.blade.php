<div>
    <div class="pt-6 px-4 xl:pl-10 xl:pr-0 max-w-7xl mx-auto mb-4 flex flex-col">
        <div class="overflow-x-auto">
            <div class="align-middle inline-block min-w-full">
                <div class="shadow overflow-hidden">
                    <div class="relative w-full h-full md:h-auto">

                        <div class="bg-white rounded-lg relative">

                            <div class="flex items-start p-5 border-b rounded-t">
                                <div class="flex items-center">
                                    <h3 class="text-2xl font-bold leading-none text-gray-900">Новое предложение</h3>
                                </div>
                            </div>

                            <form method="POST" enctype="multipart/form-data" action="{{ route('admin.offer.store') }}">
                                @csrf

                                <div class="flex flex-row border-b" wire:ignore>

                                    <!-- image  -->
                                    <div class="flex flex-row" id="upload_area" wire:ignore>
                                        <div class="flex relative p-3">
                                            <img class="h-20 w-20 rounded-lg mx-4 my-2 object-cover" id="img"
                                                src="{{ url('/image/no-image.png') }}" alt="avatar">
                                            <button type="button" id="remove_image" class="absolute top-2 right-2"
                                                style="display: none;"><img src="{{ url('/image/remove.png') }}"
                                                    class="w-5 h-5" style="cursor:pointer;"></button>
                                        </div>

                                        <div class="flex items-center">
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
                                    <div class="hidden  flex flex-row" id="upload_area_1" wire:ignore>
                                        <div class="flex relative p-3">
                                            <img class="h-20 w-20 rounded-lg mx-4 my-2 object-cover" id="img_1"
                                                src="{{ url('/image/no-image.png') }}" alt="avatar">
                                            <button type="button" id="remove_image_1" class="absolute top-2 right-2"
                                                style="display: none;"><img src="{{ url('/image/remove.png') }}"
                                                    class="w-5 h-5" style="cursor:pointer;"></button>
                                        </div>

                                        <div class="flex items-center">
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
                                    <div class="hidden flex flex-row" id="upload_area_2" wire:ignore>
                                        <div class="flex relative p-3">
                                            <img class="h-20 w-20 rounded-lg mx-4 my-2 object-cover" id="img_2"
                                                src="{{ url('/image/no-image.png') }}" alt="avatar">
                                            <button type="button" id="remove_image_2" class="absolute top-2 right-2"
                                                style="display: none;"><img src="{{ url('/image/remove.png') }}"
                                                    class="w-5 h-5" style="cursor:pointer;"></button>
                                        </div>

                                        <div class="flex items-center">
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
                                    <div class="hidden flex flex-row" id="upload_area_3" wire:ignore>
                                        <div class="flex relative p-3">
                                            <img class="h-20 w-20 rounded-lg mx-4 my-2 object-cover" id="img_3"
                                                src="{{ url('/image/no-image.png') }}" alt="avatar">
                                            <button type="button" id="remove_image_3" class="absolute top-2 right-2"
                                                style="display: none;"><img src="{{ url('/image/remove.png') }}"
                                                    class="w-5 h-5" style="cursor:pointer;"></button>
                                        </div>

                                        <div class="flex items-center">
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
                                    <div class="hidden flex flex-row" id="upload_area_4" wire:ignore>
                                        <div class="flex relative p-3">
                                            <img class="h-20 w-20 rounded-lg mx-4 my-2 object-cover" id="img_4"
                                                src="{{ url('/image/no-image.png') }}" alt="avatar">
                                            <button type="button" id="remove_image_4" class="absolute top-2 right-2"
                                                style="display: none;"><img src="{{ url('/image/remove.png') }}"
                                                    class="w-5 h-5" style="cursor:pointer;"></button>
                                        </div>

                                        <div class="flex items-center">
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
                                            <x-input-label for="name" :value="__('Название *')" />
                                            <x-text-input id="name" name="name" type="text"
                                                class="mt-2 block w-full bg-gray-50" :error="$errors->get('name')"
                                                :value="old('name')" required autofocus />
                                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                        </div>

                                        {{-- Телефон --}}
                                        <div class="col-span-6 md:col-span-2">
                                            <label for="phone"
                                                class="text-sm font-medium text-gray-900 block mb-2">Телефон</label>
                                            <input type="tel" name="phone" id="phone" wire:ignore
                                                placeholder='+ 7 (***) ***-**-**'
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 mask-phone"
                                                value="{{ old('phone') }}">
                                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                        </div>

                                        {{-- Город --}}
                                        <div class="col-span-6 md:col-span-2" id="city_div" wire:ignore>
                                            <x-admin.select-city />
                                        </div>

                                        {{-- Сущность --}}
                                        <x-admin.select-entity />

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
                                                value="{{ old('address') }}">
                                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                        </div>

                                        {{-- Описание --}}
                                        <div class="col-span-6">
                                            <label for="description"
                                                class="text-sm font-medium text-gray-900 block mb-2">Описание</label>
                                            <textarea type="text" name="description" id="description"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">{{ old('description') ?? old('description') }}</textarea>
                                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                        </div>
                                    </div>

                                    <hr class="my-5">
                                    <div class="items-center pb-6 border-gray-200 rounded-b">
                                        <div class="col-span-6">
                                            <div class="flex w-full justify-between">
                                                <label for="activity" class="inline-flex items-center">
                                                    <input id="activity" type="checkbox" checked value="1"
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                        name="activity">
                                                    <span class="ml-2 text-sm text-gray-600">Активность</span>
                                                </label>
                                                <button
                                                    class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                                    type="submit">СОХРАНИТЬ</button>
                                            </div>
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
            const maxSize = 2000000; // Максимальный размер файла 2 MB

            const sections = [{
                    input: '#image',
                    img: '#img',
                    span: '#image_span',
                    remove: '#remove_image',
                    section: '#upload_area'
                },
                {
                    input: '#image_1',
                    img: '#img_1',
                    span: '#image_span_1',
                    remove: '#remove_image_1',
                    section: '#upload_area_1'
                },
                {
                    input: '#image_2',
                    img: '#img_2',
                    span: '#image_span_2',
                    remove: '#remove_image_2',
                    section: '#upload_area_2'
                },
                {
                    input: '#image_3',
                    img: '#img_3',
                    span: '#image_span_3',
                    remove: '#remove_image_3',
                    section: '#upload_area_3'
                },
                {
                    input: '#image_4',
                    img: '#img_4',
                    span: '#image_span_4',
                    remove: '#remove_image_4',
                    section: '#upload_area_4'
                },
            ];

            function handleFileInput(file, index) {
                if (!file) return;

                const fileSize = file.size;
                const section = sections[index];
                const nextSection = sections[index + 1];

                if (fileSize > maxSize) {
                    $(section.span).html('Максимальный размер 2 МБ').css({
                        color: "rgb(239 68 68)"
                    });
                    return;
                }

                $(section.span).html(file.name).css({
                    color: "rgb(71 85 105)"
                });
                $(section.section).find('.flex.items-center').hide();

                // Скрыть кнопку "Удалить" на предыдущих секциях
                sections.forEach((s, i) => {
                    if (i !== index) $(s.remove).hide();
                });

                // Показать кнопку "Удалить" только для текущей секции
                $(section.remove).show();

                // Показать следующую секцию
                if (nextSection) {
                    $(nextSection.section).css({
                        display: "flex",
                        "flex-direction": "row"
                    });
                }

                // Предварительный просмотр изображения
                const reader = new FileReader();
                reader.onload = function(event) {
                    $(section.img).attr('src', event.target.result);
                };
                reader.readAsDataURL(file);

                // Синхронизация файла с <input type="file">
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                $(section.input)[0].files = dataTransfer.files;
            }

            function resetFileInput(index) {
                const section = sections[index];
                const prevSection = sections[index - 1];
                const nextSection = sections[index + 1];

                $(section.input).val('');
                $(section.img).attr('src', `{{ url('/image/no-image.png') }}`);
                $(section.span).html('Выберите файл').css({
                    color: "rgb(71 85 105)"
                });
                $(section.remove).hide();
                $(section.section).find('.flex.items-center').show();

                // Если удаляем последнее изображение, снова показываем эту секцию
                $(section.section).css({
                    display: "flex",
                    "flex-direction": "row"
                });

                // Скрыть следующие секции
                if (nextSection) {
                    for (let i = index + 1; i < sections.length; i++) {
                        $(sections[i].section).hide();
                        $(sections[i].input).val('');
                        $(sections[i].img).attr('src', `{{ url('/image/no-image.png') }}`);
                        $(sections[i].span).html('Выберите файл').css({
                            color: "rgb(71 85 105)"
                        });
                        $(sections[i].remove).hide();
                    }
                }

                // Показать кнопку "Удалить" в предыдущей секции
                if (prevSection) {
                    $(prevSection.remove).show();
                }
            }

            function enableDragAndDrop(index) {
                const section = sections[index];

                $(section.section).on('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).css('background-color', '#f1f5f9'); // Подсветка
                });

                $(section.section).on('dragleave', function() {
                    $(this).css('background-color', ''); // Убираем подсветку
                });

                $(section.section).on('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).css('background-color', '');

                    const files = e.originalEvent?.dataTransfer?.files || [];
                    if (files.length > 0) {
                        handleFileInput(files[0], index);
                    }
                });
            }

            sections.forEach((section, index) => {
                $(section.input).on('change', function() {
                    handleFileInput(this.files[0], index);
                });

                $(section.remove).on('click', function() {
                    resetFileInput(index);
                });

                enableDragAndDrop(index);
            });

        });
    </script>
    @vite(['resources/js/mask_phone.js'])
</div>
