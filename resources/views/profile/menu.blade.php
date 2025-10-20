<div class="flex flex-col basis-full lg:basis-1/5 ">
    <div class="flex flex-row">
        <div class="bg-white p-2 rounded-md my-1 basis-full flex lg:hidden" id="button-profile-menu">
            <p class="mx-auto text-lg uppercase" id="p-profile-menu">Закрыть</p>
        </div>
    </div>
    <div class="bg-white rounded-md pb-3 lg:m-3 my-3 block" id="select-profile-menu">
        <ul class="m-6 text-sm">

            <li class="p-2 hover:text-gray-500 rounded-md @if (request()->routeIs('dashboard')) bg-gray-100 @endif"><a
                    href="{{ route('dashboard') }}">Моя
                    страница</a>
            </li>

            {{-- 
            TODO - доделать мессенджер
            <li
                class="p-2 hover:text-gray-500 rounded-md @if (request()->routeIs('messenger')) bg-gray-100 @endif">
                <a href="{{ route('messenger') }}" class="relative">Мессенджер
                    <!-- Бейдж с уведомлением -->
                    @php
                        $unreadCount = auth()->user()->unreadMessagesCount();
                    @endphp

                    @if ($unreadCount)
                        <span
                            class="absolute right-[-1rem] top-[-0.5rem] bg-blue-500 px-2 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>
            </li> --}}

            <li class="p-2 hover:text-gray-500 rounded-md @if (request()->routeIs('mycompanies.*')) bg-gray-100 @endif"><a
                    href="{{ route('mycompanies.index') }}">Мои компании</a>
            </li>

            <li class="p-2 hover:text-gray-500 rounded-md @if (request()->routeIs('mygroups.*')) bg-gray-100 @endif"><a
                    href="{{ route('mygroups.index') }}">Мои сообщества</a>
            </li>

            <li class="p-2 hover:text-gray-500 rounded-md @if (request()->routeIs('myoffers.*')) bg-gray-100 @endif"><a
                    href="{{ route('myoffers.index') }}">Товары и услуги</a>
            </li>

            <li class="p-2 hover:text-gray-500 rounded-md @if (request()->routeIs('myplaces.*')) bg-gray-100 @endif"><a
                    href="{{ route('myplaces.index') }}">Мои места</a>
            </li>

            <li class="p-2 hover:text-gray-500 rounded-md @if (request()->routeIs('mycommunities.*')) bg-gray-100 @endif"><a
                    href="{{ route('mycommunities.index') }}">Мои общины</a>
            </li>

            <li class="p-2 hover:text-gray-500 rounded-md @if (request()->routeIs('myjobs.*')) bg-gray-100 @endif"><a
                    href="{{ route('myjobs.index') }}">Работа, вакансии</a>
            </li>

            <hr class="mt-3 mb-2">

            <li class="p-2 hover:text-gray-500 rounded-md @if (request()->routeIs('profile.*')) bg-gray-100 @endif"><a
                    href="{{ route('profile.edit') }}">Настройки</a>
            </li>

            <li class="p-2 hover:text-gray-500 rounded-md @if (request()->routeIs('questions')) bg-gray-100 @endif"><a
                    href="{{ route('questions') }}">Частые
                    вопросы</a>
            </li>

            @role('super-admin|moderator')
                <hr class="my-3">
                <li class="p-2 hover:text-gray-500 rounded-md"><a href="{{ route('admin.dashboard') }}">Админ панель</a>
                </li>
            @endrole

        </ul>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#button-profile-menu").click(function() {
            $("#select-profile-menu").toggle();
            $("#button-profile-menu").css({
                'background-color': 'rgb(226 232 240)'
            })

            if ($('#p-profile-menu').text() == 'меню') {
                $("#p-profile-menu").text('закрыть')
            } else {
                $("#p-profile-menu").text('меню')
            }
        });
    })
</script>
