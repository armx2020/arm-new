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

                            <form method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.telegram_group.update', ['telegram_group' => $entity->id]) }}">
                                @csrf
                                @method('PUT')

                                <div class="flex items-start p-5 border-b rounded-t">
                                    <div class="flex items-center mb-4">
                                        <h3 class="text-2xl font-bold leading-none text-gray-900">{{ $entity->username}}
                                        </h3>
                                    </div>
                                </div>

                                <div class="p-6 space-y-6">
                                    <div class="grid grid-cols-6 gap-6">
                                        <div class="col-span-6">
                                            <label for="name"
                                                class="text-sm font-medium text-gray-900 block mb-2">Название телеграмм
                                                группы</label>
                                            <input type="text" name="username" id="username"
                                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                                required autofocus value="{{ old('username', $entity->username) }}">
                                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
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
@endsection
