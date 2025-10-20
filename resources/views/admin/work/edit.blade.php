@extends('admin.layouts.app')
@section('content')
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
                                <div class="flex items-center my-4">
                                    <h3 class="text-2xl font-bold leading-none text-gray-900">{{ $work->name }}</h3>
                                </div>
                            </div>
                            <div class="p-6 space-y-6">
                                <form method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.work.update', ['work' => $work->id]) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-6 gap-6">
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="name"
                                                class="text-sm font-medium text-gray-900 block mb-2">Название *</label>
                                            <input type="text" name="name" id="firstname"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                required value="{{ $work->name }}">
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="address"
                                                class="text-sm font-medium text-gray-900 block mb-2">Адрес</label>
                                            <input type="text" name="address" id="address"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                value="{{ $work->address }}">
                                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                        </div>
                                        <div class="col-span-6">
                                            <label for="description"
                                                class="text-sm font-medium text-gray-900 block mb-2">Описание</label>
                                            <input type="text" name="description" id="description"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                value="{{ $work->description }}">
                                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                        </div>
                                        <div class="col-span-6">
                                            <label for="city" class="text-sm font-medium text-gray-900 block mb-2">Город
                                                *</label>
                                            <select name="city" class="w-full" id="dd_city">
                                                <option value='{{ $work->city->id }}'>{{ $work->city->name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-span-6">
                                            <label for="entity"
                                                class="text-sm font-medium text-gray-900 block mb-2">Инициатор</label>
                                            <select name="entity" id="entity"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                id="entity">
                                                @foreach ($entities as $entity)
                                                    <option value="{{ $entity->id }}" @selected($entity->id == $work->entity_id)>{{ $entity->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="items-center py-6 border-gray-200 rounded-b">
                                        <button
                                            class="w-full text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                            type="submit">Save work</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
                                query: params.term, // search term
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
        });
        $(document).ready(function() {
            $('#parent').on('change', function() {
                if (this.value == 'User') {
                    $('#User').show();
                    $('#Company').hide();
                    $('#Group').hide();
                } else if (this.value == 'Company') {
                    $('#User').hide();
                    $('#Company').show();
                    $('#Group').hide();
                } else if (this.value == 'Group') {
                    $('#User').hide();
                    $('#Company').hide();
                    $('#Group').show();
                } else {
                    $('#User').hide();
                    $('#Company').hide();
                    $('#Group').hide();
                }
            });
        });
    </script>
@endsection
