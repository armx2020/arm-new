@extends('admin.layouts.app')
@section('content')
    <div class="pt-6 px-4 xl:pl-10 xl:pr-0 max-w-7xl mx-auto mb-4 flex flex-col">
        <div class="overflow-x-auto w-full">
            <div class="align-middle inline-block min-w-full">
                <div class="shadow overflow-hidden">
                    <div class="relative w-full h-full md:h-auto">

                        @if (session('success'))
                            <div class="my-4 bg-green-100 px-6 py-5 text-base text-green-700" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="bg-white rounded-lg relative">

                            <form method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.user.update', ['user' => $user->id]) }}">
                                @csrf
                                @method('PUT')

                                <input name="image_remove" type="text" id="image_remove" class="hidden"
                                    style="z-index:-10;" />

                                <div class="flex flex-row border-b" id="upload_area">
                                    <div class="flex relative p-3">
                                        @if ($user->image == null)
                                            <img class="h-20 w-20 rounded-full m-4 object-cover" id="img"
                                                src="{{ url('/image/no-image.png') }}" alt="{{ $user->firstname }} avatar">
                                        @else
                                            <img class="h-20 w-20 rounded-full m-4 object-cover" id="img"
                                                src="{{ storage_url($user->image) }}"
                                                alt="{{ $user->firstname }} avatar">
                                        @endif
                                        <button type="button" id="remove_image" class="absolute top-2 right-2"
                                            @if ($user->image == null) style="display: none;"
                                            @else
                                            style="display: block;" @endif><img
                                                src="{{ url('/image/remove.png') }}" class="w-5 h-5"
                                                style="cursor:pointer;"></button>
                                    </div>

                                    <div class="flex items-center">
                                        <label class="input-file relative inline-block">
                                            <input name="image" type="file" accept=".jpg,.jpeg,.png" id="image"
                                                class="absolute opacity-0 block w-0 h-0" style="z-index:-1;" />
                                            <span
                                                class="relative inline-block align-middle text-center p-2 rounded-lg w-full text-slate-600"
                                                style="cursor:pointer;">Выберите файл или перетащите сюда</span>
                                        </label>
                                    </div>

                                </div>


                                <div class="p-6 space-b-6">
                                    <div class="grid grid-cols-6 gap-6">

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="firstname" class="text-sm font-medium text-gray-900 block mb-2">Имя
                                                *</label>
                                            <input type="text" name="firstname" id="firstname"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                value="{{ $user->firstname }}" required>
                                            <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3" wire:ignore>
                                            <x-admin.select-city :selectedCity="$user->city"/>
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="email" class="text-sm font-medium text-gray-900 block mb-2">Email
                                                *</label>
                                            <input type="email" name="email" id="email"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                value="{{ $user->email }}" required>
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="phone"
                                                class="text-sm font-medium text-gray-900 block mb-2">Телефон</label>
                                            <input type="tel" name="phone" id="phone"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                value="{{ $user->phone }}">
                                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                        </div>

                                    </div>

                                    <hr class="my-5">

                                    <div class="grid grid-cols-6 gap-6">



                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="whatsapp"
                                                class="text-sm font-medium text-gray-900 block mb-2">Whatsapp</label>
                                            <input type="text" name="whatsapp" id="whatsapp"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                value="{{ $user->whatsapp }}">
                                            <x-input-error :messages="$errors->get('whatsapp')" class="mt-2" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="telegram"
                                                class="text-sm font-medium text-gray-900 block mb-2">Telegram</label>
                                            <input type="text" name="telegram" id="telegram"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                value="{{ $user->telegram }}">
                                            <x-input-error :messages="$errors->get('telegram')" class="mt-2" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="instagram"
                                                class="text-sm font-medium text-gray-900 block mb-2">Instagram</label>
                                            <input type="text" name="instagram" id="instagram"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                value="{{ $user->instagram }}">
                                            <x-input-error :messages="$errors->get('instagram')" class="mt-2" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="vkontakte"
                                                class="text-sm font-medium text-gray-900 block mb-2">Vkontakte</label>
                                            <input type="text" name="vkontakte" id="vkontakte"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                value="{{ $user->vkontakte }}">
                                            <x-input-error :messages="$errors->get('vkontakte')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="items-center py-6 border-gray-200 rounded-b">
                                        <button
                                            class="w-full text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                            type="submit">Сохранить</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="bg-white rounded-lg relative my-5">

                            <form method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.user.update-password', ['user' => $user->id]) }}">
                                @csrf
                                @method('PUT')

                                <div class="p-6 space-b-6">

                                    <div class="grid grid-cols-6 gap-6">

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="password"
                                                class="text-sm font-medium text-gray-900 block mb-2">Новый пароль
                                                (8 символов)</label>
                                            <input type="text" name="password" id="password" value="12345678"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                required>
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="password_confirmation"
                                                class="text-sm font-medium text-gray-900 block mb-2">Подтвердите
                                                новый пароль</label>
                                            <input type="text" name="password_confirmation" id="password_confirmation"
                                                value="12345678"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                required>
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>

                                    </div>

                                    <div class="items-center py-6 border-gray-200 rounded-b">
                                        <button
                                            class="w-full text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                            type="submit">Сохранить</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="py-6 px-4 xl:pl-10 xl:pr-0 max-w-7xl mx-auto rounded-t-lg">
        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Сущности пользователя</h1>
        <div class="mt-3">
            @if ($entities->isEmpty())
                <div class="bg-white shadow p-4">
                    <div class="flex items-center text-center">
                        <h3 class="text-xl font-normal mx-auto">{{ $emptyEntity }}</h3>
                    </div>
                </div>
            @else
                <div class="mb-4 flex flex-col">
                    <div class="overflow-x-auto">
                        <div class="align-middle inline-block min-w-full">
                            <div class="shadow overflow-hidden">
                                @php
                                    $colorMap = $colorMap ?? [];
                                @endphp
                                <table class="table-fixed min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-200">
                                    <tr>
                                        @foreach ($selectedColumns as $column)
                                            @if($column != 'img')
                                                <th scope="col"
                                                    class="p-4 text-left text-xs font-medium text-gray-500 uppercase max-w-[20rem] truncate">
                                                    <button class="cursor-default" wire:click.prevent='sortBy("{{ $column }}")'
                                                            role="button">
                                                        {{ __('column.' . $column) }}</button>
                                                </th>
                                            @else
                                                <th scope="col"
                                                    class="p-4 text-left text-xs font-medium text-gray-500 max-w-[20rem] truncate cursor-default">
                                                    {{ __('column.' . $column) }}
                                                </th>
                                            @endif
                                        @endforeach

                                        <th scope="col" class="p-4">
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($entities as $entity)
                                        @php
                                            $duplicateClass = $colorMap[$entity->id] ?? null;

                                            if ($duplicateClass) {
                                                $rowClass =
                                                    $duplicateClass .
                                                    ' ' .
                                                    ($entity->activity ? 'text-gray-900' : 'text-gray-600');
                                            } else {
                                                if ($entity->activity) {
                                                    $rowClass = 'bg-white text-gray-900';
                                                } else {
                                                    $rowClass = 'bg-gray-300 text-gray-600';
                                                }
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-200 {{ $rowClass }}">
                                            @foreach ($selectedColumns as $column)
                                                <td
                                                    class="p-4 pt-2 pb-2 text-base text-left break-all max-w-[20rem] truncate">
                                                    @switch($column)
                                                        @case('city_id')
                                                        {{ $entity->city->name }}
                                                        @break

                                                        @case('region_id')
                                                        {{ $entity->region->name }}
                                                        @break

                                                        @case('user_id')
                                                        {{ $user->firstname ?? '' }}
                                                        @break

                                                        @case('category_id')
                                                        {{ $entity->category ? $entity->category->name : '-' }}
                                                        @break

                                                        @case('type')
                                                        {{ $entity->type ? $entity->type->name : '-' }}
                                                        @break

                                                        @case('img')
                                                        @if(isset($entity->primaryImage->path))
                                                            <img class="w-8 h-8 object-cover rounded-lg" src="{{ storage_url($entity->primaryImage->path) }}" alt="Image">
                                                        @else
                                                            -
                                                        @endif
                                                        @break

                                                        @default
                                                        <a class="text-blue-800 hover:text-blue-600"
                                                           href="{{ route('admin.' . $entityName . '.edit', [$entityName => $entity->id]) }}">
                                                            {{ $entity->$column ?? '-' }}
                                                        </a>
                                                    @endswitch

                                                </td>
                                            @endforeach

                                            <td class="text-nowrap px-2 py-2 flex">

                                                <x-dropdown align="top" width="48">
                                                    <x-slot name="trigger">
                                                        <button
                                                            class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">

                                                            <div class="ms-1">
                                                                <svg class="w-5 h-5" aria-hidden="true"
                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                     fill="currentColor" viewBox="0 0 4 15">
                                                                    <path
                                                                        d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                        </button>
                                                    </x-slot>
                                                    <x-slot name="content">
                                                        <div
                                                            class="block px-4 text-sm font-medium hover:bg-gray-100 cursor-pointer">
                                                            <button
                                                                class="w-full h-full py-2 flex items-center space-x-2"
                                                                wire:click.prevent='changeActivity("{{ $entity->id }}")'
                                                                role="button">
                                                                @if ($entity->activity)
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                         class="w-4 h-4 fill-red-500"
                                                                         viewBox="0 0 576 512">
                                                                        <path
                                                                            d="M192 64C86 64 0 150 0 256S86 448 192 448l192 0c106 0 192-86 192-192s-86-192-192-192L192 64zm192 96a96 96 0 1 1 0 192 96 96 0 1 1 0-192z" />
                                                                    </svg>
                                                                    <span class="text-red-500">деактивировать</span>
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                         class="w-4 h-4 fill-green-500"
                                                                         viewBox="0 0 576 512">
                                                                        <path
                                                                            d="M384 128c70.7 0 128 57.3 128 128s-57.3 128-128 128l-192 0c-70.7 0-128-57.3-128-128s57.3-128 128-128l192 0zM576 256c0-106-86-192-192-192L192 64C86 64 0 150 0 256S86 448 192 448l192 0c106 0 192-86 192-192zM192 352a96 96 0 1 0 0-192 96 96 0 1 0 0 192z" />
                                                                    </svg>
                                                                    <span class="text-green-500">активировать</span>
                                                                @endif
                                                            </button>
                                                        </div>
                                                        <form
                                                            action="{{ route('admin.' . $entityName . '.destroy', [$entityName => $entity->id]) }}"
                                                            method="post"
                                                            class="block px-4 text-sm font-medium text-red-500 hover:bg-gray-100 cursor-pointer">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="w-full h-full py-2 flex items-center space-x-2">
                                                                <svg class="w-4 h-4 fill-red-500"
                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                     viewBox="0 0 20 20" aria-hidden="true">
                                                                    <path fill-rule="evenodd"
                                                                          d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                                                          clip-rule="evenodd"></path>
                                                                </svg>
                                                                <span class="text-red-500">Удалить</span>
                                                            </button>
                                                        </form>
                                                    </x-slot>
                                                </x-dropdown>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="p-4">
                {{ $entities->links() }}
            </div>
        </div>
    </div>
























    <script type='text/javascript'>
        $(document).ready(function() {
            if ($("#dd_city").length > 0) {
                $("#dd_city").select2({
                    ajax: {
                        url: " {{ route('cities') }}",
                        type: "post",
                        delay: 250,
                        dataType: 'json',
                        data: function(params) {
                            return {
                                query: params.term,
                                "_token": "{{ csrf_token() }}",
                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }
                });
            }

            function previewImage(file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $('#img').attr('src', event.target.result);
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
                    $('#img').attr('src', `{{ url('/image/no-image.png') }}`);
                    $('#remove_image').css({
                        "display": "none"
                    });
                } else {
                    $('.input-file input[type=file]').next().html(file.name);
                    $('.input-file input[type=file]').next().css({
                        "color": "rgb(71 85 105)"
                    });
                    $('#remove_image').css({
                        "display": "block"
                    });
                    previewImage(file);
                }
            }

            $('#image').on('change', function(event) {
                var selectedFile = event.target.files[0];
                handleFile(selectedFile);
            });

            $('#remove_image').on('click', function() {
                $('#image').val('');
                $('#image_remove').val('delete');
                $('#img').attr('src', `{{ url('/image/no-image.png') }}`);
                $('.input-file input[type=file]').next().html('Выберите файл или перетащите сюда');
                $('#remove_image').css({
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

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    $('#image').prop('files', dataTransfer.files);
                }
            });
        });
    </script>
@endsection
