@extends('layouts.app')

@section('title')
    <title>Армянский справочник для армян России и мира</title>
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Армянский справочник для армян России и мираУ">
@endsection

@section('scripts')
    <script src="{{ url('/select2.min.js') }}"></script>
    <script src="{{ url('/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ url('/jquery-ui.min.js') }}"></script>
    @vite(['resources/css/select.css'])
@endsection

@section('content')
    {{--  Хлебные крошки --}}
    <nav class="hidden md:block mb-2 mt-3 lg:mt-5 rounded-md mx-auto text-xs sm:text-sm md:text-md px-1">

        @php
            $homeUrl = route('home');
            $entityTypeUrl = route('inform-us.place');

            if ($region && $region !== 'russia') {
                $homeUrl = route('home', ['regionTranslit' => $region]);
            }
        @endphp

        <ol class="list-reset flex flex-nowrap overflow-hidden">
            <li class="text-neutral-500">
                <a href="{{ $homeUrl }}" class="truncate">
                    Главная
                </a>
            </li>
            <li>
                <a href="{{ $homeUrl }}">
                    <span class="mx-2 text-neutral-500">/</span>
                </a>
            </li>
            <li class="text-neutral-500">
                <a href="{{ $entityTypeUrl }}" class="truncate">
                    Сообщите нам об интересно месте
                </a>
            </li>
        </ol>
    </nav>

    <section>
        <div class="flex flex-col sm:justify-center items-center py-6">

            @if (session('success'))
                <div class="mt-5 w-full sm:max-w-xl rounded-lg bg-green-100 px-6 py-5 text-base text-green-700"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="w-full sm:max-w-xl my-6 px-6 py-6 bg-white overflow-hidden sm:rounded-lg">

                <h3 class="text-xl font-semibold">Добавить место</h3>
                <p class="text-sm">Укажите данные места. После проверки, он окажеться на портале</p>
                <hr class="mt-4">

                <!-- Session Status -->

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('inform-us.place') }}" enctype="multipart/form-data">
                    @csrf

                    @if (session('error'))
                        <x-input-error :messages="session('error')" class="mt-2 mb-3" />
                    @endif

                    @if ($errors->any())
                        {!! implode('', $errors->all("<div class='text-red-500'>:message</div>")) !!}
                    @endif

                    <div class="step-one">
                        <div class="mt-4">
                            <x-input-label for="checkbox-group" :value="__('Выберите категорию *')" />
                            <div class="flex border-2 rounded-lg p-4 mt-1 @if (count($errors->get('fields')) > 0) border-1 border-red-300 @endif"
                                id="checkbox-group">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 w-full">

                                    @foreach ($categories as $item)
                                        <div class="flex flex-col gap-1">
                                            <div class="flex">
                                                <input type="radio" name="category" value="{{ $item->id }}"
                                                    @if (is_array(old('category')) && in_array($item->id, old('category'))) checked @endif
                                                    class="checkbox-{{ $item->id }} shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                                    id="checkbox-{{ $item->id }}">
                                                <label for="checkbox-group-{{ $item->id }}"
                                                    class="text-base text-black ms-3">{{ $item->name }}</label>
                                            </div>
                                            @foreach ($item->categories as $child)
                                                <div class="flex">
                                                    <input type="radio" name="category" value="{{ $item->id }}"
                                                        @if (is_array(old('category')) && in_array($child->id, old('category'))) checked @endif
                                                        class="checkbox-{{ $loop->parent->iteration }} shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                                        id="checkbox-{{ $item->id }}">
                                                    <label for="checkbox-{{ $item->id }}"
                                                        class="text-sm text-gray-500 ms-3">{{ $child->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-two hidden">
                        <!-- сity -->
                        <div class="my-3">
                            <label for="city" class="text-sm font-medium text-gray-900 block mb-2">Город</label>
                            <select name="city" style="border-color: rgb(209 213 219); width: 100%" id="city">
                                <option value='1'>Выберете город</option>
                            </select>
                        </div>

                        <!-- name -->
                        <div class="mt-4">
                            <label for="name" class="text-sm font-medium text-gray-900 block mb-2">Название</label>
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- address -->
                        <div class="mt-4">
                            <label for="address" class="text-sm font-medium text-gray-900 block mb-2">Адрес (не обязательно)</label>
                            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address"
                                :value="old('address')" />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- phone -->
                        <div class="mt-4">
                            <label for="phone" class="text-sm font-medium text-gray-900 block mb-2">Телефон (не обязательно)</label>
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                                class="mt-1 block w-full mask-phone" placeholder='+7 (***) ***-**-**' :value="old('phone')" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- web -->
                        <div class="mt-4">
                            <label for="web" class="text-sm font-medium text-gray-900 block mb-2">Веб-сайт (не обязательно)</label>
                            <x-text-input id="web" class="block mt-1 w-full" type="text" name="web"
                                placeholder='https://***********.**' :value="old('web')" />
                            <x-input-error :messages="$errors->get('web')" class="mt-2" />
                        </div>

                        <!-- telegram -->
                        <div class="mt-4">
                            <label for="telegram" class="text-sm font-medium text-gray-900 block mb-2">Telegram (не обязательно)</label>
                            <x-text-input id="telegram" class="block mt-1 w-full" type="text" name="telegram"
                                placeholder='https://t.me/******' :value="old('telegram')" />
                            <x-input-error :messages="$errors->get('telegram')" class="mt-2" />
                        </div>


                        <!-- whatsapp -->
                        <div class="mt-4">
                            <label for="whatsapp" class="text-sm font-medium text-gray-900 block mb-2">Whatsapp (не обязательно)</label>
                            <x-text-input id="whatsapp" class="block mt-1 w-full" type="text" name="whatsapp"
                                placeholder='https://wa.me/***********' :value="old('whatsapp')" />
                            <x-input-error :messages="$errors->get('whatsapp')" class="mt-2" />
                        </div>

                        <!-- vkontakte -->
                        <div class="mt-4">
                            <label for="vkontakte" class="text-sm font-medium text-gray-900 block mb-2">Vkontakte (не обязательно)</label>
                            <x-text-input id="vkontakte" class="block mt-1 w-full" type="text" name="vkontakte"
                                placeholder='https://vk.com/***********' :value="old('vkontakte')" />
                            <x-input-error :messages="$errors->get('vkontakte')" class="mt-2" />
                        </div>

                        <!-- instagram -->
                        <div class="mt-4">
                            <label for="instagram" class="text-sm font-medium text-gray-900 block mb-2">Instagram (не обязательно)</label>
                            <x-text-input id="instagram" class="block mt-1 w-full" type="text" name="instagram"
                                placeholder='https://instagram.com/*******' :value="old('instagram')" />
                            <x-input-error :messages="$errors->get('instagram')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <textarea id="description"
                                class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text"
                                name="description" :value="old('description')" placeholder="Описание (не обязательно)"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>

                    <div class="step-three hidden">
                        <div class="border-b min-h-auto overflow-hidden pb-2">
                            <div id="sortable-slots"></div>
                            <div id="add-slot-container"></div>
                        </div>
                        <hr class="mb-4">
                    </div>

                    <div class="flex items-center justify-between mt-4">

                        <div class="step-one w-full">
                            <div class="flex items-center justify-start">
                                <button id="next-to-step-two" type="button"
                                    class="px-3 inline-flex items-center py-2 border border-blue-500 rounded-md font-semibold text-xs text-blue-500 uppercase tracking-widest hover:bg-blue-800 focus:bg-blue-800 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 px-3">
                                    {{ __('далее') }}
                                </button>
                            </div>
                        </div>


                        <div class="step-two hidden w-full">
                            <div class="flex justify-between">
                                <div class="flex items-center justify-start">
                                    <button id="back-to-step-one" type="button"
                                        class="px-3 inline-flex items-center py-2 border border-blue-500 rounded-md font-semibold text-xs text-blue-500 uppercase tracking-widest hover:bg-blue-800 focus:bg-blue-800 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 px-3">
                                        {{ __('назад') }}
                                    </button>
                                </div>

                                <div class="flex items-center justify-start">
                                    <button id="next-to-step-three" type="button"
                                        class="px-3 inline-flex items-center py-2 border border-blue-500 rounded-md font-semibold text-xs text-blue-500 uppercase tracking-widest hover:bg-blue-800 focus:bg-blue-800 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 px-3">
                                        {{ __('далее') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="step-three hidden w-full">
                            <div class="flex justify-between">
                                <div class="flex items-center justify-start">
                                    <button id="back-to-step-two" type="button"
                                        class="px-3 inline-flex items-center py-2 border border-blue-500 rounded-md font-semibold text-xs text-blue-500 uppercase tracking-widest hover:bg-blue-800 focus:bg-blue-800 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 px-3">
                                        {{ __('назад') }}
                                    </button>
                                </div>

                                <div class="flex items-center justify-end">
                                    <x-primary-button class="px-3">
                                        {{ __('отправить') }}
                                    </x-primary-button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </section>

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

    <script type='text/javascript'>
        $(document).ready(function() {
            $('#next-to-step-two').on('click', function() {
                if ($('input[name="category"]').is(':checked')) {
                    $('.step-two').show();
                    $('.step-one').hide();
                    $('.step-three').hide();
                    $('#checkbox-group').css("border-color", "#e5e7eb");
                } else {
                    $('#checkbox-group').css("border-color", "red");
                }

            });

            $('#back-to-step-one').on('click', function() {
                $('.step-one').show();
                $('.step-two').hide();
                $('.step-three').hide();
            });

            $('#next-to-step-three').on('click', function() {
                if ($('#name').val()) {
                    $('.step-three').show();
                    $('.step-one').hide();
                    $('.step-two').hide();
                    $('#name').css("border-color", "#e5e7eb");
                } else {
                    $('#name').css("border-color", "red");
                }

            });

            $('#back-to-step-two').on('click', function() {
                $('.step-two').show();
                $('.step-one').hide();
                $('.step-three').hide();
            });

            if ($("#city").length > 0) {
                $("#city").select2({
                    ajax: {
                        url: " {{ route('cities') }}",
                        type: "GET",
                        delay: 250,
                        dataType: 'json',
                        data: function(params) {
                            var query = {
                                query: params.term || '',
                                page: params.page || 1,
                                "_token": "{{ csrf_token() }}",
                            };

                            return query;
                        },
                        processResults: function(response, params) {
                            params.page = params.page || 1;
                            return {
                                results: response.results,
                                pagination: {
                                    more: response.pagination.more
                                }
                            };
                        },
                        cache: true
                    }
                });
            }

            const maxSlots = 5; // Макс размер файла — 2MB
            const maxSize = 2 * 1024 * 1024;

            const $sortable = $('#sortable-slots');
            const $addSlotContainer = $('#add-slot-container');

            $sortable.sortable({
                items: '.image-slot',
                cancel: 'input, button, label',
            });

            createEmptySlot();

            /* Создание пустого слота (поле добавки) в #add-slot-container. */
            function createEmptySlot() {
                const slotCount = $sortable.find('.image-slot').length;
                if (slotCount >= maxSlots) {
                    return;
                }

                $addSlotContainer.empty();

                const $slot = cloneSlotTemplate();

                initSlot($slot, /* isEmptySlot */ true);

                $addSlotContainer.append($slot);
            }

            /* Создание "обычного" слота (после выбора файла) в #sortable-slots.  */
            function createFilledSlot(file) {
                const $slot = cloneSlotTemplate();
                initSlot($slot, false);

                const fileInput = $slot.find('.file-input')[0];
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                const reader = new FileReader();
                reader.onload = (e) => {
                    $slot.find('.preview-img').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);

                $slot.find('.file-label').addClass('hidden');

                $slot.find('.remove-image-btn').show();

                $sortable.append($slot);
            }

            function initSlot($slot, isEmptySlot) {
                const $fileInput = $slot.find('.file-input');
                const $removeBtn = $slot.find('.remove-image-btn');
                const $img = $slot.find('.preview-img');
                const $labelSpan = $slot.find('.file-label span');

                if (isEmptySlot) {
                    $removeBtn.hide();
                }

                $fileInput.on('change', function() {
                    const file = this.files[0];
                    if (!file) {
                        return;
                    }
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
                            $img.attr('src', e.target.result);
                        };
                        reader.readAsDataURL(file);
                        $labelSpan.text(file.name);

                        $removeBtn.show();
                    }
                });

                $removeBtn.on('click', function() {
                    $slot.remove();

                    const slotCount = $sortable.find('.image-slot').length;
                    if (slotCount < maxSlots) {
                        if ($addSlotContainer.find('.image-slot').length === 0) {
                            createEmptySlot();
                        }
                    }
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
            }

            function cloneSlotTemplate() {
                const template = document.getElementById('image-slot-template');
                return $(template.content.cloneNode(true)).find('.image-slot');
            }
        });
    </script>
@endsection

@section('body')
    @vite(['resources/js/mask_phone.js'])
@endsection
