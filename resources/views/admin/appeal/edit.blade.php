@extends('admin.layouts.app')
@section('content')
    <div class="pt-6 px-4 xl:pl-10 xl:pr-0 max-w-7xl mx-auto mb-4 flex flex-coll">
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

                            <form id="appeal_delete_form" action="{{ route('admin.appeal.destroy', $appeal->id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                            </form>

                            <form method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.appeal.update', ['appeal' => $appeal->id]) }}">
                                @csrf
                                @method('PUT')
                                <input name="image_remove_1" type="text" id="image_remove_1" class="hidden"
                                    style="z-index:-10;" />
                                <input name="image_remove_2" type="text" id="image_remove_2" class="hidden"
                                    style="z-index:-10;" />
                                <input name="image_remove_3" type="text" id="image_remove_3" class="hidden"
                                    style="z-index:-10;" />
                                <input name="image_remove_4" type="text" id="image_remove_4" class="hidden"
                                    style="z-index:-10;" />
                                <input name="image_remove_5" type="text" id="image_remove_5" class="hidden"
                                    style="z-index:-10;" />

                                <div class="flex justify-between p-5 border-b rounded-t">
                                    <div class="flex flex-col">
                                        <div class="flex items-center mb-4">
                                            <h3 class="text-2xl font-bold leading-none text-gray-900">{{ $appeal->name }}
                                                ({{ $appeal->phone }})</h3>

                                        </div>

                                        @if ($appeal->entity_id)
                                            <div class="flex items-center mb-4">
                                                <h5 class="text-lg leading-none text-gray-900">
                                                    <a href="{{ route('admin.entity.edit', ['entity' => $appeal->entity->id]) }}" 
                                                       class="text-blue-600 hover:text-blue-800 hover:underline">
                                                        {{ $appeal->entity->name }}
                                                        (id{{ $appeal->entity->id }})
                                                    </a>
                                                </h5>
                                            </div>
                                        @endif

                                        @if ($appeal->user_id)
                                            <div class="flex items-center mb-4">
                                                <h5 class="text-lg leading-none text-gray-900">
                                                    {{ $appeal->user->firstname }}
                                                    (id{{ $appeal->user->id }})</h5>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center pl-7">
                                        <div class="pr-5">
                                            <label for="activity" class="inline-flex">
                                                <div>
                                                    <input id="activity" type="checkbox" @checked($appeal->activity)
                                                        value="1"
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                        name="activity">
                                                </div>
                                                <span class="ml-2 text-gray-700">Активность</span>
                                            </label>
                                        </div>
                                        <button id="appeal_delete" type="button"
                                            class="pr-5 text-gray-700">Удалить</button>
                                    </div>

                                </div>

                                @php
                                    $images = $appeal->images()->get();
                                @endphp

                                <div class="flex flex-row border-b" wire:ignore>

                                    <!-- image 1 -->
                                    <div class="flex flex-row" id="upload_area_1">

                                        <div class="flex relative p-3">
                                            <img class="h-20 w-20 rounded-lg m-4 object-cover" id="img_1" alt="image"
                                                @if (empty($images[0])) src="{{ url('/image/no-image.png') }}" @else src="{{ asset('storage/' . $images[0]->path) }}" @endif>

                                            <button type="button" id="remove_image_1" class="absolute top-2 right-2"
                                                @if (isset($images[0]) && empty($images[1])) style="display: block;" @else style="display: none;" @endif>

                                                <img src="{{ url('/image/remove.png') }}" class="w-5 h-5"
                                                    style="cursor:pointer;">
                                            </button>
                                        </div>

                                        <div class="items-center" id="title_image_1"
                                            @if (empty($images[0])) style="display: flex;" @else style="display: none;" @endif>

                                            <label class="input-file relative inline-block">
                                                <input name="image_1" type="file" accept=".jpg,.jpeg,.png" id="image_1"
                                                    class="absolute opacity-0 block w-0 h-0" style="z-index:-1;" />
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

                                            <img class="h-20 w-20 rounded-lg m-4 object-cover" id="img_2" alt="image"
                                                @if (empty($images[1])) src="{{ url('/image/no-image.png') }}" @else src="{{ asset('storage/' . $images[1]->path) }}" @endif>

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

                                <div class="p-6 space-y-6">

                                    {{-- Сообщение --}}
                                    <div class="col-span-6">
                                        <textarea type="text" name="message" id="message"
                                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">{{ old('description', $appeal->message) }}</textarea>
                                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                                    </div>

                                    <div class="items-center py-6 border-gray-200 rounded-b">
                                        <button
                                            class="w-full text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                            type="submit">Сохранить</button>
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

            $('#appeal_delete').on("click", function() {
                $('#appeal_delete_form').submit()
            });

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

            // image 5
            $('#image_5').on('change', function() {
                updatePreview(this, '#img_5', '#image_span_5', '#title_image_5', '#remove_image_5',
                    '#remove_image_4', null);
            });
            $('#remove_image_5').on('click', function() {
                removeFile('#image_5', '#img_5', '#image_span_5', null, '#remove_image_3',
                    '#remove_image_5', '#upload_area_5, #title_image_5', '#image_remove_5');
            });


            ['#upload_area_5', '#upload_area_1', '#upload_area_2', '#upload_area_3', '#upload_area_4'].forEach(
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
@endsection
