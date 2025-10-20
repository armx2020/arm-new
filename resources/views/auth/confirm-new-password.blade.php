<x-guest-layout>

    <form method="POST" action="{{ route('confirm-phone') }}">
        @csrf

        <div class="w-full sm:max-w-md px-1 py-4 bg-white overflow-hidden sm:rounded-lg z-50">
            <div class="flex items-center justify-between my-3">
                <p class="text-lg font-bold text-gray-900">
                    СБРОС ПАРОЛЯ
                </p>
                <p class="text-sm text-gray-400">
                    количество попыток: {{ $count }}
                </p>
            </div>

            <hr class="my-4">

            @if (session('error'))
                <x-input-error :messages="session('error')" class="mt-2 mb-3" />
            @endif

            <!-- confirm_phone -->
            <div>
                <x-input-label for="code" :value="__('Введите последние 4 цифры номера с которого мы звоним')" />
                <x-text-input id="code" class="block my-2 w-full" placeholder="последние 4 цифры" type="number"
                    name="code" max=9999 min=0000 :value="old('code')" require autofocus />
                @if ($message)
                    <p class="text-sm text-red-600 my-1">{{ $message }}</p>
                @endif

            </div>

            <div class="flex items-center justify-between mt-4">
                <a href="{{ route('register') }}"
                    class="popup-close underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    ВЕРНУТЬСЯ НАЗАД
                </a>
                <x-primary-button class="ml-4">
                    {{ __('Отправить') }}
                </x-primary-button>
            </div>
        </div>

    </form>
</x-guest-layout>
