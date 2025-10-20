@extends('layouts.app')

@section('title')
    <title>Добавить фото - {{ $entity->name }}</title>
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Добавить фото - {{ $entity->name }}">
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
            $entityTypeUrl = route("$entityTranscription.index");

            if ($region && $region !== 'russia') {
                $homeUrl = route('home', ['regionTranslit' => $region]);
                $entityTypeUrl = route("$entityTranscription.region", ['regionTranslit' => $region]);
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
                    {{ $entityName }}
                </a>
            </li>
            <li>
                <a href="{{ $entityTypeUrl }}">
                    <span class="mx-2 text-neutral-500">/</span>
                </a>
            </li>
            <li class="text-neutral-500">
                <a href="{{ route($entityShowRoute, ['idOrTranscript' => $entity->id]) }}" class="truncate">
                    {{ $entity->name }}
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

                <h3 class="text-xl font-semibold">Добавить фото - <br>{{ $entity->name }}</h3>
                <p class="text-sm my-1">Добавленные изображения будут доступны после модерации</p>
                <hr class="mt-4">

                <!-- Session Status -->

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('entity.photo.update', ['idOrTranscript' => $entity->id]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    @if (session('error'))
                        <x-input-error :messages="session('error')" class="mt-2 mb-3" />
                    @endif

                    <div class="border-b min-h-auto overflow-hidden py-2">
                        <div id="sortable-slots"></div>
                        <div id="add-slot-container"></div>
                    </div>

                    <div class="flex items-center justify-center mt-4">

                        <div class="flex items-center justify-end">
                            <x-primary-button class="px-3">
                                {{ __('отправить') }}
                            </x-primary-button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </section>

    <template id="image-slot-template">
        <div
            class="image-slot border border-dashed border-gray-300 relative p-2 float-left flex items-center space-x-2 rounded-md ml-2 my-1">

            <img class="preview-img w-12 h-12 object-cover rounded-md" src="{{ url('/image/no-image.png') }}">

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

    <input type="hidden" id="maxSlots" value="{{ 20 - $entity->images(false)->count() }}">

    <script type="text/javascript">
        $(document).ready(function() {

            const maxSlots = parseInt($('#maxSlots').val(), 10);
            // Макс размер файла — 2MB
            const maxSize = 2 * 1024 * 1024;

            const $sortable = $('#sortable-slots');
            const $addSlotContainer = $('#add-slot-container');

            $sortable.sortable({
                items: '.image-slot',
                cancel: 'input, button, label',
            });

            createEmptySlot();

            /**
             * Создание пустого слота (поле добавки) в #add-slot-container.
             */
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

            /**
             * Создание "обычного" слота (после выбора файла) в #sortable-slots.
             */
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
