@extends('admin.layouts.app')
@section('content')
    <div class="pt-6 px-4 xl:pl-10 xl:pr-0 max-w-7xl mx-auto mb-4 flex flex-col">
        <div class="overflow-x-auto">
            <div class="align-middle inline-block min-w-full">
                <div class="shadow overflow-hidden">
                    <div class="relative w-full h-full md:h-auto">
                        <div class="bg-white rounded-lg relative">

                            <div class="flex items-start p-5 border-b rounded-t">
                                <div class="flex items-center">
                                    <h3 class="text-2xl font-bold leading-none text-gray-900">Новый пользователь</h3>
                                </div>
                            </div>

                            <form method="POST" enctype="multipart/form-data" action="{{ route('admin.user.store') }}">
                                @csrf

                                <div class="flex flex-row border-b" id="upload_area">
                                    <div class="flex relative p-3">
                                        <img class="h-20 w-20 rounded-full m-4 object-cover" id="img"
                                            src="{{ url('/image/no-image.png') }}" alt="avatar">
                                        <button type="button" id="remove_image" class="absolute top-2 right-2"
                                            style="display: none;"><img src="{{ url('/image/remove.png') }}" class="w-5 h-5"
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

                                <x-input-error :messages="$errors->get('image')" class="mt-2" />

                                <div class="p-6 space-y-6">
                                    <div class="grid grid-cols-6 gap-6">

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="firstname" class="text-sm font-medium text-gray-900 block mb-2">Имя
                                                *</label>
                                            <input type="text" name="firstname" id="firstname"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                :value="old('firstname')" required>
                                            <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3" wire:ignore>
                                            <x-admin.select-city />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="email" class="text-sm font-medium text-gray-900 block mb-2">Email</label>
                                            <input type="email" name="email" id="email"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                :value="old('email')">
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="phone"
                                                class="text-sm font-medium text-gray-900 block mb-2">Телефон *</label>
                                            <input type="tel" name="phone" id="phone"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5 mask-phone"
                                                :value="old('phone')" required>
                                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="password"
                                                class="text-sm font-medium text-gray-900 block mb-2">Пароль *</label>
                                            <input type="text" name="password" id="password" value="12345678"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                required>
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="password_confirmation"
                                                class="text-sm font-medium text-gray-900 block mb-2">Подтвердите
                                                Пароль *</label>
                                            <input type="text" name="password_confirmation" id="password_confirmation"
                                                value="12345678"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                required>
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>

                                    </div>

                                    <hr class="my-5">

                                    <div class="grid grid-cols-6 gap-6">

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="whatsapp"
                                                class="text-sm font-medium text-gray-900 block mb-2">Whatsapp</label>
                                            <input type="text" name="whatsapp" id="whatsapp"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                :value="old('whatsapp')">
                                            <x-input-error :messages="$errors->get('whatsapp')" class="mt-2" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="telegram"
                                                class="text-sm font-medium text-gray-900 block mb-2">Telegram</label>
                                            <input type="text" name="telegram" id="telegram"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                :value="old('telegram')">
                                            <x-input-error :messages="$errors->get('telegram')" class="mt-2" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="instagram"
                                                class="text-sm font-medium text-gray-900 block mb-2">Instagram</label>
                                            <input type="text" name="instagram" id="instagram"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                :value="old('instagram')">
                                            <x-input-error :messages="$errors->get('instagram')" class="mt-2" />
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="vkontakte"
                                                class="text-sm font-medium text-gray-900 block mb-2">Vkontakte</label>
                                            <input type="text" name="vkontakte" id="vkontakte"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                :value="old('vkontakte')">
                                            <x-input-error :messages="$errors->get('vkontakte')" class="mt-2" />
                                        </div>

                                    </div>
                                    <div class="items-center py-6 border-gray-200 rounded-b">
                                        <button
                                            class="w-full text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                            type="submit">Добавить</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type='text/javascript'>
        $(document).ready(function() {
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
                    $('#image').prop('files', files);
                }
            });
        });
    </script>

    @vite(['resources/js/mask_phone.js'])
@endsection
