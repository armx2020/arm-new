<section class="my-6 w-full">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Удалить учетную запись') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Как только ваша учетная запись будет удалена, все ее ресурсы и данные будут удалены безвозвратно. Прежде чем удалить свою учетную запись, пожалуйста, загрузите любые данные или информацию, которые вы хотите сохранить.') }}
        </p>
    </header>

    <x-danger-button id="confirm-user-deletion-button" class="my-5">{{ __('Удалить') }}</x-danger-button>

    <div id="confirm-user-deletion" class="hidden fixed inset-0 px-4 min-h-screen sm:px-0 z-50" focusable>
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        <div class="my-16 mx-auto opacity-100 translate-y-0 sm:scale-100 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full md:max-w-md lg:max-w-lg ">
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6 w-full">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="mt-6">
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4" placeholder="{{ __('Password') }}" />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button id="confirm-user-deletion-close" x-on:click="$dispatch('close')">
                        {{ __('Отменить') }}
                    </x-secondary-button>

                    <x-danger-button class="ml-3">
                        {{ __('Удалить') }}
                    </x-danger-button>
                </div>
            </form>
        </div>
    </div>
    <script type='text/javascript'>
        $(document).ready(function() {
            $("#confirm-user-deletion-button").click(function() {
                $("#confirm-user-deletion").toggle();
            });
            $("#confirm-user-deletion-close").click(function() {
                $("#confirm-user-deletion").toggle();
            });
        });
    </script>
</section>