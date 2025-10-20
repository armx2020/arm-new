<div>
    <div class="pt-6 px-4 xl:pl-10 xl:pr-0 max-w-7xl mx-auto mb-4 flex flex-col">
        <div class="overflow-x-auto">
            <div class="align-middle inline-block min-w-full">
                <div class="shadow overflow-hidden">
                    <div class="relative w-full h-full md:h-auto">

                        <div class="bg-white rounded-lg relative mb-8">

                            <form id="card-form" method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.entity.store') }}">
                                @csrf

                                <div class="flex justify-between p-5 border-b rounded-t">
                                    <div class="flex items-center">
                                        <h3 class="text-2xl font-bold leading-none text-gray-900">Новая сущность</h3>
                                    </div>
                                    <div class="flex items-center pl-7">
                                        <div class="pr-5">
                                            <label for="activity" class="inline-flex">
                                                <div>
                                                    <input id="activity" type="checkbox" checked value="1"
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                        name="activity">
                                                </div>
                                                <span class="ml-2 text-gray-700">Активность</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-row w-full justify-between border-b">

                                    <div class="min-h-auto overflow-hidden" wire:ignore>
                                        <div id="sortable-slots"></div>
                                        <div id="add-slot-container"></div>
                                    </div>

                                    <!-- Logo  -->
                                    <div class="flex flex-row bg-gray-100 max-h-28 border-r" id="upload_area">
                                        <div class="flex relative">
                                            <img class="h-20 w-20 rounded-lg m-4  object-cover" id="logo"
                                                src="{{ url('/image/no-image.png') }}" alt="image">
                                            <button type="button" id="remove_logo"
                                                class="absolute top-2 right-2 hidden"><img
                                                    src="{{ url('/image/remove.png') }}" class="w-5 h-5"
                                                    style="cursor:pointer;"></button>
                                        </div>

                                        <div class="flex items-center">
                                            <label class="input-file relative inline-block">
                                                <input name="logotype" type="file" accept=".jpg,.jpeg,.png"
                                                    id="logotype" class="absolute opacity-0 block w-0 h-0"
                                                    style="z-index:-1;" />
                                                <span
                                                    class="relative inline-blockalign-middle text-center p-2 w-full text-slate-600"
                                                    style="cursor:pointer;">Выберите логотип</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div>
                                    <x-input-error :messages="$errors->get('image')" />
                                </div>



                                <x-profile.alert />

                                <div class="p-6 space-y-3">
                                    <div class="grid grid-cols-6 gap-4">

                                        {{-- Название --}}
                                        <div class="col-span-6 md:col-span-2">
                                            <x-input-label for="name" :value="__('Название *')" />
                                            <x-text-input id="name" name="name" type="text"
                                                class="mt-2 block w-full bg-gray-50" :error="$errors->get('name')" :value="old('name')"
                                                required autofocus />
                                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                        </div>


                                        {{-- Адрес --}}
                                        <div class="col-span-6 md:col-span-4" wire:ignore>
                                            <label for="city"
                                                class="text-sm font-medium text-gray-900 block mb-2">Адрес (не
                                                обязательно)</label>
                                            <select class="form-control select2-address" id="address" name="address"
                                                style="border-color: rgb(209 213 219); width: 100%"></select>
                                            <input type="hidden" id="latitude" name="latitude">
                                            <input type="hidden" id="longitude" name="longitude">
                                            <input type="hidden" id="city" name="city">
                                            <input type="hidden" id="region" name="region">
                                        </div>

                                        {{-- Телефон --}}
                                        <div class="col-span-6 ms:col-span-3">
                                            <label for="phone"
                                                class="text-sm font-medium text-gray-900 block mb-2">Телефон</label>
                                            <input type="tel" name="phone" id="phone" wire:ignore
                                                placeholder='+ 7 (***) ***-**-**'
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 mask-phone"
                                                value="{{ old('phone') }}">
                                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                        </div>


                                        {{-- Тип сущности --}}
                                        <div class="col-span-6 ms:col-span-3">
                                            <label for="selectedType"
                                                class="text-sm font-medium text-gray-900 block mb-2">Тип
                                                сущности</label>
                                            <select name="type" id="selectedType" wire:model.live="selectedType"
                                                required
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                                                <option selected value=""> -- не выбрано --</option>
                                                @foreach ($typies as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Направления --}}
                                        @isset($this->selectedType)
                                            @if ($categories !== null && count($categories) > 0)
                                                <div class="col-span-6">
                                                    <label for="fields"
                                                        class="text-sm font-medium text-gray-900 block mb-2">Направление</label>
                                                    <div class="flex border-2 rounded-lg p-4  mt-1" id="checkbox-group">
                                                        <div class="grid grid-cols-3 gap-4 w-full">

                                                            @foreach ($categories as $item)
                                                                <div class="flex flex-col gap-1">
                                                                    <div class="flex">
                                                                        @if (count($item->categories) < 1)
                                                                            <input type="checkbox" name="fields[]"
                                                                                value="{{ $item->id }}"
                                                                                @if (is_array(old('fields')) && in_array($item->id, old('fields'))) checked @endif
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
                                                                            <input type="checkbox" name="fields[]"
                                                                                value="{{ $child->id }}"
                                                                                @if (is_array(old('fields')) && in_array($child->id, old('fields'))) checked @endif
                                                                                class="checkbox-{{ $loop->parent->iteration }} shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                                                                id="checkbox-{{ $child->id }}">
                                                                            <label for="checkbox-{{ $child->id }}"
                                                                                class="text-sm text-gray-500 ms-3">{{ $child->name }}</label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                    <x-input-error class="mt-2" :messages="$errors->get('fields')" />
                                                </div>
                                            @endif
                                        @endisset

                                        {{-- Директор --}}
                                        <div class="col-span-6">
                                            <x-input-label for="director" :value="__('Директор')" />
                                            <x-text-input id="director" name="director" type="text"
                                                class="mt-2 block w-full bg-gray-50" :error="$errors->get('director')"
                                                :value="old('director')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('director')" />
                                        </div>

                                        {{-- Описание --}}
                                        <div class="col-span-6">
                                            <label for="description"
                                                class="text-sm font-medium text-gray-900 block mb-2">Описание</label>
                                            <textarea type="text" name="description" id="description" rows="10"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">{{ old('description') ?? old('description') }}</textarea>
                                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                        </div>

                                        {{-- Сортировка --}}
                                        <div class="col-span-6">
                                            <label for="sort_id"
                                                class="text-sm font-medium text-gray-900 block mb-2">Сортировка
                                                *</label>
                                            <input type="number" name="sort_id" id="sort_id"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                value=0 required>
                                            <x-input-error :messages="$errors->get('sort_id')" class="mt-2" />
                                        </div>

                                        {{-- Пользователь --}}
                                        <x-admin.select-user />

                                        @role('super-admin')
                                            {{-- Модератор --}}
                                            <x-admin.select-moderator />
                                        @endrole

                                    </div>

                                    {{-- Соц. ссылки --}}
                                    <hr class="my-5">
                                    <div class="grid grid-cols-6 gap-4">

                                        <div class="col-span-6">
                                            <x-input-label for="video_url" :value="__('Cсылка на видео из youtube или rutube')" />
                                            <x-text-input id="video_url" name="video_url" type="text"
                                                class="mt-1 block w-full bg-gray-50"
                                                placeholder='https://youtube.com/****' :value="old('video_url')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('video_url')" />
                                        </div>

                                        <div class="col-span-6">
                                            <x-input-label for="paymant_link" :value="__('Платёжная ссылка')" />
                                            <x-text-input id="paymant_link" name="paymant_link" type="text"
                                                class="mt-1 block w-full bg-gray-50"
                                                placeholder='https://*************' :value="old('paymant_link')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('paymant_link')" />
                                        </div>

                                        <div class="col-span-6">
                                            <x-input-label for="web" :value="__('Веб')" />
                                            <x-text-input id="web" name="web" type="text"
                                                class="mt-1 block w-full bg-gray-50"
                                                placeholder='https://***********.**' :value="old('web')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('web')" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <x-input-label for="whatsapp" :value="__('Whatsapp')" />
                                            <x-text-input id="whatsapp" name="whatsapp" type="text"
                                                class="mt-1 block w-full bg-gray-50"
                                                placeholder='https://wa.me/***********' :value="old('whatsapp')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('whatsapp')" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <x-input-label for="telegram" :value="__('Телеграм')" />
                                            <x-text-input id="telegram" name="telegram" type="text"
                                                class="mt-1 block w-full bg-gray-50" placeholder='https://t.me/******'
                                                :value="old('telegram')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('telegram')" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <x-input-label for="vkontakte" :value="__('Вконтакте')" />
                                            <x-text-input id="vkontakte" name="vkontakte" type="text"
                                                class="mt-1 block w-full bg-gray-50"
                                                placeholder='https://vk.com/***********' :value="old('vkontakte')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('vkontakte')" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <x-input-label for="instagram" :value="__('Инстаграм')" />
                                            <x-text-input id="instagram" name="instagram" type="text"
                                                class="mt-1 block w-full bg-gray-50"
                                                placeholder='https://instagram.com/*******' :value="old('instagram')" />
                                            <x-input-error class="mt-2" :messages="$errors->get('instagram')" />
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="image-slot-template">
        <div
            class="image-slot border border-dashed border-gray-300 relative p-2 float-left flex items-center space-x-2 rounded-md ml-2 my-1">

            <img class="preview-img w-20 h-20 object-cover rounded-md" src="{{ url('/image/no-image.png') }}">

            <button type="button" class="remove-image-btn absolute top-3 right-3" style="display: none;">
                <img src="{{ url('/image/remove.png') }}" class="w-5 h-5">
            </button>

            <label class="file-label cursor-pointer flex-grow text-center">
                <input type="file" name="images[]" class="file-input hidden" accept=".jpg,.jpeg,.png">
                <span class="text-sm text-gray-500">
                    <div class="text-left px-2">Выберите файл или</div>
                    <div class="text-left px-2">перетащите сюда</div>
                </span>
            </label>
        </div>
    </template>

    <script type="text/javascript">
        $(document).ready(function() {

            // Инициализация Яндекс.Карт
            ymaps.ready(init);

            function init() {
                // Инициализация Select2 для поиска адреса
                $('.select2-address').select2({
                    placeholder: "Начните вводить адрес (город, улица, дом)",
                    minimumInputLength: 3,
                    ajax: {
                        transport: function(params, success, failure) {
                            // Используем API Яндекс.Карт для поиска полного адреса
                            ymaps.geocode(params.data.q, {
                                results: 5,
                                boundedBy: [ // Границы России
                                    [41.185, 19.638], // Юго-западная точка
                                    [81.858, 180.0] // Северо-восточная точка
                                ],
                                json: true,
                            }).then(function(res) {
                                var addresses = res.GeoObjectCollection.featureMember.filter(
                                    function(item) {
                                        // Проверяем, что адрес относится к России
                                        var country = item.GeoObject.metaDataProperty
                                            .GeocoderMetaData.Address.Components
                                            .find(c => c.kind === 'country');
                                        return country && country.name === 'Россия';
                                    }).map(
                                    function(item) {
                                        var address = item.GeoObject.metaDataProperty
                                            .GeocoderMetaData.text;
                                        var components = item.GeoObject.metaDataProperty
                                            .GeocoderMetaData.Address.Components;
                                        var coordinates = [
                                            parseFloat(item.GeoObject.Point.pos.split(
                                                ' ')[1]), // Широта
                                            parseFloat(item.GeoObject.Point.pos.split(
                                                ' ')[0]) // Долгота
                                        ];

                                        // Извлекаем город, улицу и дом из компонентов
                                        var city = components.find(c => c.kind ===
                                            'locality')?.name || '';
                                        var street = components.find(c => c.kind ===
                                            'street')?.name || '';
                                        var house = components.find(c => c.kind === 'house')
                                            ?.name || '';

                                        // Получаем именно край/область/республику (исключаем федеральные округа)
                                        var region = components.find(c =>
                                            c.kind === 'province' &&
                                            !c.name.includes('федеральный округ')
                                        )?.name || '';

                                        // Альтернативный вариант - берем AdministrativeArea из метаданных
                                        if (!region) {
                                            region = item.GeoObject.metaDataProperty
                                                .GeocoderMetaData.Address
                                                .Components.find(c => ['region', 'republic',
                                                    'krai', 'oblast'
                                                ].some(
                                                    type => c.kind.includes(type)
                                                ))?.name || '';
                                        }

                                        return {
                                            id: address,
                                            text: address,
                                            city: city,
                                            region: region,
                                            street: street,
                                            house: house,
                                            coordinates: coordinates,
                                            data: item.GeoObject
                                        };
                                    });

                                success({
                                    results: addresses
                                });
                            }, failure);
                        }
                    },
                    templateResult: function(address) {
                        // Кастомизация отображения результатов
                        if (address.loading) return address.text;

                        var $container = $('<div>').addClass('address-item').append(
                            $('<div>').addClass('address-full').text(address.text || ''),
                            $('<div>').addClass('address-details').append(
                                address.city ? $('<span>').addClass('city').text(address.city) : '',
                                address.street ? $('<span>').addClass('street').text(', ' + address
                                    .street) : '',
                                address.house ? $('<span>').addClass('house').text(', ' + address
                                    .house) : ''
                            )
                        );

                        return $container;
                    },
                    templateSelection: function(address) {
                        // Кастомизация отображения выбранного элемента
                        return address.text || address.id;
                    }
                });

                $('.select2-address').on('select2:select', function(e) {
                    var data = e.params.data;
                    if (data.coordinates) {
                        $('#latitude').val(data.coordinates[0]);
                        $('#longitude').val(data.coordinates[1]);
                        $('#city').val(data.city);
                        $('#region').val(data.region);
                    }
                });
            }

            // image

            const maxSlots = 20;
            const maxSize = 20 * 1024 * 1024; // 2MB

            const $sortable = $('#sortable-slots');
            const $addSlotContainer = $('#add-slot-container');

            $sortable.sortable({
                items: '.image-slot',
                cancel: 'input, button, label',
            });

            createEmptySlot();

            // При клике по контейнеру пустого слота – открываем диалог выбора
            $addSlotContainer.on('click', function(e) {
                if (e.target === this) {
                    let $emptySlot = $(this).find('.image-slot').first();
                    if ($emptySlot.length === 0) {
                        createEmptySlot();
                        $emptySlot = $(this).find('.image-slot').first();
                    }
                    $emptySlot.find('.file-input').trigger('click');
                }
            });

            $(document).on('paste', function(e) {
                let totalCount = $sortable.find('.image-slot').length;
                if (totalCount >= maxSlots) {
                    $addSlotContainer.hide();
                    return;
                }
                const clipboardItems = (e.originalEvent.clipboardData || e.clipboardData).items;
                if (clipboardItems) {
                    for (let i = 0; i < clipboardItems.length; i++) {
                        if (clipboardItems[i].type.indexOf("image") !== -1) {
                            const file = clipboardItems[i].getAsFile();
                            if (file) {
                                if (file.size > maxSize) {
                                    alert('Файл больше 2МБ!');
                                    return;
                                }
                                totalCount = $sortable.find('.image-slot').length;
                                if (totalCount >= maxSlots) {
                                    $addSlotContainer.hide();
                                    return;
                                }
                                createFilledSlot(file);
                                maybeCreateEmptySlot();
                            }
                        }
                    }
                }
            });

            function createEmptySlot() {
                const slotCount = $sortable.find('.image-slot').length;
                if (slotCount >= maxSlots) return;
                $addSlotContainer.empty().show();
                const $slot = cloneSlotTemplate();
                initSlotForNew($slot, true);
                $addSlotContainer.append($slot);
            }

            function createFilledSlot(file) {
                const $slot = cloneSlotTemplate();
                initSlotForNew($slot, false);
                const newId = 'new_' + (newImageCounter++);
                $slot.attr('data-id', newId);
                const fileInput = $slot.find('.file-input')[0];
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
                const reader = new FileReader();
                reader.onload = function(e) {
                    $slot.find('.preview-img').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
                $slot.find('.file-label').addClass('hidden');
                $slot.find('.remove-image-btn').show();
                $sortable.append($slot);
            }

            function initSlotForNew($slot, isEmptySlot) {
                const $fileInput = $slot.find('.file-input');
                const $removeBtn = $slot.find('.remove-image-btn');
                const $preview = $slot.find('.preview-img');
                const $labelSpan = $slot.find('.file-label span');

                // Сохраняем флаг пустоты слота
                $slot.data('isEmpty', isEmptySlot);

                $fileInput.on('click', function(e) {
                    e.stopPropagation();
                });

                if (isEmptySlot) {
                    $removeBtn.hide();
                }

                $fileInput.on('change', function() {
                    const file = this.files[0];
                    if (!file) return;
                    if (file.size > maxSize) {
                        alert('Файл больше 2МБ!');
                        $fileInput.val('');
                        return;
                    }
                    if (isEmptySlot) {
                        createFilledSlot(file);
                        createEmptySlot();
                        $slot.remove();
                    } else {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $preview.attr('src', e.target.result);
                        };
                        reader.readAsDataURL(file);
                        $labelSpan.text(file.name);
                        $removeBtn.show();
                    }
                });

                $removeBtn.on('click', function(e) {
                    e.stopPropagation();
                    $slot.remove();
                    maybeCreateEmptySlot();
                });

                $slot.on('dragover', function(e) {
                    e.preventDefault();
                    $slot.css('background-color', '#f1f5f9');
                });
                $slot.on('dragleave', function(e) {
                    e.preventDefault();
                    $slot.css('background-color', '');
                });
                $slot.on('drop', function(e) {
                    e.preventDefault();
                    $slot.css('background-color', '');
                    const files = e.originalEvent.dataTransfer.files;
                    if (files && files.length > 0) {
                        $fileInput[0].files = files;
                        $fileInput.trigger('change');
                    }
                });

                $slot.on('click', function(e) {
                    if ($slot.data('isEmpty') && !$(e.target).closest(
                            '.remove-image-btn, .file-input, label').length) {
                        $fileInput.trigger('click');
                    }
                });
            }

            function maybeCreateEmptySlot() {
                const slotCount = $sortable.find('.image-slot').length;
                if (slotCount < maxSlots) {
                    $addSlotContainer.show();
                    if ($addSlotContainer.find('.image-slot').length === 0) {
                        createEmptySlot();
                    }
                } else {
                    $addSlotContainer.hide();
                }
            }

            let newImageCounter = 1;

            function cloneSlotTemplate() {
                const template = document.getElementById('image-slot-template');
                return $(template.content.cloneNode(true)).find('.image-slot');
            }

            // Логотип
            function previewImage(file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $('#logo').attr('src', event.target.result);
                };
                reader.readAsDataURL(file);
            }

            function handleFile(file) {
                var fileSize = file.size;
                var maxSize = 2000000; // 2 MB

                if (fileSize > maxSize) {
                    $('.input-file input[type=file]').next().html('максимальный размер 2 мб');
                    $('.input-file input[type=file]').next().css({
                        "color": "rgb(239 68 68)"
                    });
                    $('#logo').attr('src', `{{ url('/image/no-image.png') }}`);
                    $('#remove_logo').css({
                        "display": "none"
                    });
                } else {
                    $('.input-file input[type=file]').next().html(file.name);
                    $('.input-file input[type=file]').next().css({
                        "color": "rgb(71 85 105)"
                    });
                    $('#remove_logo').css({
                        "display": "block"
                    });
                    previewImage(file);
                }
            }

            $('#logotype').on('change', function(event) {
                var selectedFile = event.target.files[0];
                handleFile(selectedFile);
            });

            $('#remove_logo').on('click', function() {
                $('#logotype').val('');
                $('#logo').attr('src', `{{ url('/image/no-image.png') }}`);
                $('.input-file input[type=file]').next().html('Выберите логотип');
                $('#remove_logo').css({
                    "display": "none"
                });
            });

            var uploadArea = $('#upload_area');

            uploadArea.on('dragover', function(event) {
                event.preventDefault();
                event.stopPropagation();
                uploadArea.addClass('bg-gray-200');
            });

            uploadArea.on('dragleave', function(event) {
                event.preventDefault();
                event.stopPropagation();
                uploadArea.removeClass('bg-gray-200');
            });

            uploadArea.on('drop', function(event) {
                event.preventDefault();
                event.stopPropagation();
                uploadArea.removeClass('bg-gray-200');

                var files = event.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    var file = files[0];
                    handleFile(file);
                    $('#logotype').prop('files', files);
                }
            });

        });
    </script>
    @vite(['resources/js/mask_phone.js'])
    <script src="{{ url('/jquery-ui.min.js') }}"></script>
</div>
