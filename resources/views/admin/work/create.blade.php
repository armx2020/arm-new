@extends('admin.layouts.app')
@section('content')
    <div class="pt-6 px-4 xl:pl-10 xl:pr-0 max-w-7xl mx-auto mb-4 flex flex-col">
        <div class="overflow-x-auto">
            <div class="align-middle inline-block min-w-full">
                <div class="shadow overflow-hidden">
                    <div class="relative w-full h-full md:h-auto">
                        <div class="bg-white rounded-lg relative">
                            <div class="flex items-start p-5 border-b rounded-t">
                                <div class="flex items-center mb-4">
                                    <h3 class="text-2xl font-bold leading-none text-gray-900">Новая работа</h3>
                                </div>
                            </div>
                            <div class="p-6 space-y-6">
                                <form method="POST" enctype="multipart/form-data" action="{{ route('admin.work.store') }}">
                                    @csrf
                                    <div class="grid grid-cols-6 gap-6">
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="name"
                                                class="text-sm font-medium text-gray-900 block mb-2">Название *</label>
                                            <input type="text" name="name" id="firstname"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                required :value="old('name')">
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="address"
                                                class="text-sm font-medium text-gray-900 block mb-2">Адрес</label>
                                            <input type="text" name="address" id="address"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                :value="old('address')">
                                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                        </div>
                                        <div class="col-span-6">
                                            <label for="description"
                                                class="text-sm font-medium text-gray-900 block mb-2">Описание</label>
                                            <input type="text" name="description" id="description"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                :value="old('description')">
                                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                        </div>
                                        <div class="col-span-6">
                                            <label for="city" class="text-sm font-medium text-gray-900 block mb-2">Город
                                                *</label>
                                            <select name="city" class="w-full" id="city">
                                                <option value='1'>-- select city --</option>
                                            </select>
                                        </div>
                                        <div class="col-span-6">
                                            <label for="type" class="text-sm font-medium text-gray-900 block mb-2">Тип
                                                *</label>
                                            <select name="type"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5">
                                                <option value='vacancy'>Вакансия</option>
                                                <option value='resume'>Резюмэ</option>
                                            </select>
                                        </div>
                                        <div class="col-span-6">
                                            <label for="entity"
                                                class="text-sm font-medium text-gray-900 block mb-2">Инициатор</label>
                                            <select name="entity" id="entity"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                id="entity">
                                                @foreach ($entities as $entity)
                                                    <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="items-center py-6 border-gray-200 rounded-b">
                                        <button
                                            class="w-full text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                            type="submit">Добавить</button>
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
            if ($("#city").length > 0) {
                $("#city").select2({
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
    </script>
@endsection
