@extends('layouts.app')

@section('title')
    <title>Армянский справочник для армян России и мира - Мои места</title>
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Армянский справочник для армян России и мира - Мои места">
@endsection

@section('scripts')
    <script src="{{ url('/select2.min.js') }}"></script>
    <script src="{{ url('/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ url('/jquery-ui.min.js') }}"></script>
    @vite(['resources/css/select.css'])
    <script src="https://api-maps.yandex.ru/2.1/?apikey={{ config('services.yandex.geocoder_key') }}&lang=ru_RU"
    type="text/javascript"></script>
@endsection

@section('content')
    <div class="flex flex-col lg:flex-row mx-auto my-10">

        @include('profile.menu')

        <div class="flex flex-col basis-full lg:basis-4/5 lg:m-3 my-3 lg:ml-5">
            <div class="flex flex-col basis-full">
                <div class="flex flex-col md:flex-row basis-full bg-white rounded-md p-1 lg:p-5 relative">
                    <form id="card-form" method="post" action="{{ route('myplaces.update', ['myplace' => $entity->id]) }}"
                        class="w-full" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="w-full">
                            <h2 class="text-xl">Редактировать сообщество</h2>
                            <hr class="w-full h-2 my-2">
                        </div>

                        @php
                            $images = $entity->images()->get();
                        @endphp

                        <div class="border-b min-h-auto overflow-hidden pb-2" wire:ignore>
                            <div id="sortable-slots"></div>
                            <div id="add-slot-container"></div>
                        </div>

                        <div>
                            <x-input-error :messages="$errors->get('image')" />
                        </div>

                        <div class="my-3">
                            <x-input-label for="name" :value="__('Название')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                :value="old('name', $entity->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        {{-- Адрес --}}
                        <div class="my-3">
                            <label for="city" class="text-sm font-medium text-gray-900 block mb-2">Адрес (не
                                обязательно)</label>
                            <select class="form-control select2-address" id="address" name="address"
                                style="border-color: rgb(209 213 219); width: 100%"></select>
                            <input type="hidden" id="latitude" name="latitude" value="{{ $entity->lat }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ $entity->lon }}">
                            <input type="hidden" id="city" name="city" value="{{ $entity->city->name }}">
                            <input type="hidden" id="region" name="region" value="{{ $entity->region->name }}">
                        </div>

                        <script>
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

                                            var $container = $(
                                                '<div class="address-item">' +
                                                '<div class="address-full">' + address.text + '</div>' +
                                                '<div class="address-details">' +
                                                (address.city ? '<span class="city">' + address.city + '</span>' : '') +
                                                (address.street ? '<span class="street">, ' + address.street +
                                                    '</span>' : '') +
                                                (address.house ? '<span class="house">, ' + address.house + '</span>' :
                                                    '') +
                                                '</div>' +
                                                '</div>'
                                            );

                                            return $container;
                                        },
                                        templateSelection: function(address) {
                                            // Кастомизация отображения выбранного элемента
                                            return address.text || address.id;
                                        }
                                    });

                                    // После инициализации Select2
                                    var initialAddress = {
                                        id: "{{ $entity->address }}",
                                        text: "{{ $entity->address }}",
                                        coordinates: [{{ $entity->lat }}, {{ $entity->lon }}],
                                        city: "{{ $entity->city->name }}",
                                    };

                                    if (initialAddress.id) {
                                        var $select = $('.select2-address');
                                        var option = new Option(initialAddress.text, initialAddress.id, true, true);
                                        $select.append(option).trigger('change');

                                        // Установка дополнительных данных
                                        $select.data('select2').$container.data('address-data', initialAddress);

                                        // Заполнение скрытых полей
                                        $('#latitude').val(initialAddress.coordinates[0]);
                                        $('#longitude').val(initialAddress.coordinates[1]);
                                        $('#city').val(initialAddress.city);
                                    }

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
                            });
                        </script>

                        <div class="my-3">
                            <x-input-label for="description" :value="__('Описание (не обязательно)')" />
                            <x-textarea id="description" name="description" class="mt-1 block w-full">
                                {{ old('description', $entity->description) }}
                            </x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="my-3">
                            <x-input-label for="checkbox-group" :value="__('Выберите категорию *')" />
                            <div class="flex border-2 rounded-lg p-4 mt-1">
                                <div class="grid grid-cols-3 gap-4 w-full">

                                    @foreach ($categories as $item)
                                        <div class="flex flex-col gap-1">
                                            <div class="flex">
                                                @if (count($item->categories) < 1)
                                                    <input type="radio" name="category" value="{{ $item->id }}"
                                                        @checked($entity->category_id == $item->id)
                                                        @if (is_array(old('category')) && in_array($item->id, old('category'))) checked @endif
                                                        class="checkbox-{{ $loop->iteration }} shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                                        id="checkbox-{{ $item->id }}">
                                                    <label for="checkbox-{{ $item->id }}"
                                                        class="text-sm text-gray-500 ms-3">{{ $item->name }}</label>
                                                @else
                                                    <label for="checkbox-group-{{ $loop->iteration }}"
                                                        class="text-base text-black ms-3">{{ $item->name }}</label>
                                                @endif
                                            </div>
                                            @foreach ($item->categories as $child)
                                                <div class="flex pl-4">
                                                    <input type="radio" name="category" value="{{ $child->id }}"
                                                        @checked($entity->category_id == $child->id)
                                                        class="checkbox-{{ $loop->parent->iteration }} shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                                        id="checkbox-{{ $child->id }}">
                                                    <label for="checkbox-{{ $child->id }}"
                                                        class="text-sm text-gray-400 ms-3">{{ $child->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('category')" />
                        </div>

                        <div class="my-3">
                            <x-input-label for="phone" :value="__('Телефон (не обязательно)')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                                :value="old('phone', $entity->phone)" autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        <div class="my-3">
                            <x-input-label for="whatsapp" :value="__('Whatsapp (не обязательно)')" />
                            <x-text-input id="whatsapp" name="whatsapp" type="text" class="mt-1 block w-full"
                                placeholder='https://wa.me/***********' :value="old('whatsapp', $entity->whatsapp)" autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('whatsapp')" />
                        </div>

                        <div class="my-3">
                            <x-input-label for="web" :value="__('Веб (не обязательно)')" />
                            <x-text-input id="web" name="web" type="text" class="mt-1 block w-full"
                                placeholder='https://***********.**' :value="old('web', $entity->web)" autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('web')" />
                        </div>

                        <div class="my-3">
                            <x-input-label for="video_url" :value="__('Cсылка на видео из youtube или rutube')" />
                            <x-text-input id="video_url" name="video_url" type="text" class="mt-1 block w-full"
                                placeholder='https://youtube.com/****' :value="old('video_url', $entity->video_url)" />
                            <x-input-error class="mt-2" :messages="$errors->get('video_url')" />
                        </div>

                        <div class="my-3">
                            <x-input-label for="telegram" :value="__('Телеграм (не обязательно)')" />
                            <x-text-input id="telegram" name="telegram" type="text" class="mt-1 block w-full"
                                placeholder='https://t.me/******' :value="old('telegram', $entity->telegram)" autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('telegram')" />
                        </div>

                        <div class="my-3">
                            <x-input-label for="vkontakte" :value="__('Вконтакте (не обязательно)')" />
                            <x-text-input id="vkontakte" name="vkontakte" type="text" class="mt-1 block w-full"
                                placeholder='https://vk.com/***********' :value="old('vkontakte', $entity->vkontakte)" autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('vkontakte')" />
                        </div>

                        <div class="my-3">
                            <x-input-label for="instagram" :value="__('Инстаграм (не обязательно)')" />
                            <x-text-input id="instagram" name="instagram" type="text" class="mt-1 block w-full"
                                placeholder='https://instagram.com/*******' :value="old('instagram', $entity->instagram)" autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('instagram')" />
                        </div>

                        <div class="my-5">
                            <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="flex basis-full bg-gray-200 rounded-md p-5 my-6 text-sm">
                    <form method="post" action="{{ route('myplaces.destroy', ['myplace' => $entity->id]) }}"
                        class="w-full text-center">
                        @csrf
                        @method('delete')

                        <div class="m-2 flex flex-row justify-between basis-full">
                            <div class="text-lg font-medium text-gray-900 flex">
                                {{ __('Чтобы удалить, нажмите') }}
                            </div>
                            <x-danger-button class="flex">
                                {{ __('Удалить') }}
                            </x-danger-button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <template id="image-slot-template">
        <div class="image-slot border border-dashed border-gray-300 relative p-2 float-left
                flex items-center space-x-2 rounded-md ml-2 my-1"
            data-id="">

            <img class="preview-img w-20 h-20 object-cover rounded-md" src="{{ url('/image/no-image.png') }}">

            <button type="button" class="remove-image-btn absolute top-3 right-3" style="display: none;">
                <img src="{{ url('/image/remove.png') }}" class="w-5 h-5">
            </button>

            <label class="file-label cursor-pointer flex-grow text-center">
                <input type="file" class="file-input hidden" accept=".jpg,.jpeg,.png">
                <span class="text-sm text-gray-500">
                    <div class="text-left px-2">Выберите файл или</div>
                    <div class="text-left px-2">перетащите сюда</div>
                </span>
            </label>
        </div>
    </template>

    <script type="text/javascript">
        $(document).ready(function() {
            const maxSlots = 20;
            const maxSize = 20 * 1024 * 1024; // 2MB
            let newImageCounter = 1;

            const $sortable = $('#sortable-slots');
            const $addSlotContainer = $('#add-slot-container');
            const $form = $('#card-form');

            let existingImages = @json($images);
            existingImages = existingImages.sort((a, b) => (a.sort_id || 0) - (b.sort_id || 0));
            existingImages.forEach(img => {
                createExistingSlot(img);
            });
            createEmptySlot();

            $sortable.sortable({
                items: '.image-slot',
                cancel: 'input, button, label'
            });

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

            function createExistingSlot(image) {
                const $slot = cloneSlotTemplate();
                $slot.attr('data-id', image.id);
                $slot.find('.preview-img').attr('src', '/storage/' + image.path);
                $slot.find('.file-label').addClass('hidden');
                $slot.find('.remove-image-btn').show().on('click', function(e) {
                    e.stopPropagation();
                    $slot.remove();
                    maybeCreateEmptySlot();
                });
                initDragAndDrop($slot);
                $sortable.append($slot);
            }

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

            function initDragAndDrop($slot) {
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
                        $slot.find('.file-input')[0].files = files;
                        $slot.find('.file-input').trigger('change');
                    }
                });
            }

            function cloneSlotTemplate() {
                const template = document.getElementById('image-slot-template');
                return $(template.content.cloneNode(true)).find('.image-slot');
            }

            $form.on('submit', function(e) {
                $form.find('input[type="hidden"][name^="images["]').remove();
                const $slots = $sortable.find('.image-slot');
                $slots.each(function(index) {
                    const $slot = $(this);
                    const slotId = $slot.attr('data-id');
                    const sortId = index;
                    $form.append(
                        `<input type="hidden" name="images[${index}][id]" value="${slotId}">`);
                    $form.append(
                        `<input type="hidden" name="images[${index}][sort_id]" value="${sortId}">`
                        );
                    const $fileInput = $slot.find('.file-input');
                    if ($fileInput.length) {
                        $fileInput.attr('name', `images[${index}][file]`);
                    }
                });
            });
        });
    </script>
@endsection

@section('body')
    @vite(['resources/js/mask_phone.js'])
@endsection
