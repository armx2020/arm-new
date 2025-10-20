<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto pr-4 sm:pr-6 lg:pr-8">
        <div class="flex justify-between h-16">
            <div class="flex">



                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px xl:flex">

                    <div class="w-24 items-center flex">
                        <a class="" href="{{ route('home') }}">
                            <img src="{{ url('/image/logo.png') }}" class="" alt="logo" />
                        </a>
                    </div>

                    @if (Auth::user()->hasRole('super-admin'))
                        @foreach ($menu as $link)
                            @if (count($link['sub']) > 0)
                                <div class="hidden sm:flex sm:items-center sm:ms-6">
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                <div>{{ $link['name'] }} </div>

                                                <div class="ms-1">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            @foreach ($link['sub'] as $sub)
                                                <x-dropdown-link :href="route($sub['route'])">
                                                    {{ $sub['name'] }}
                                                </x-dropdown-link>
                                            @endforeach
                                        </x-slot>
                                    </x-dropdown>
                                </div>
                            @else
                                <x-nav-link :href="route($link['route'])" :active="request()->routeIs($link['routeIs'])">
                                    {{ $link['name'] }}

                                    @if ($link['name'] == 'Сообщения')
                                        @php
                                            $messagesCount = App\Models\Appeal::active()->count();
                                        @endphp
                                        @if ($messagesCount)
                                            <div
                                                class="mb-2 bottom-auto bg-red-300 mx-1 rounded-full px-2 py-1 text-center align-baseline text-xs font-bold leading-none text-white">
                                                @if ($messagesCount > 9)
                                                    9+
                                                @elseif($messagesCount <= 9 && $messagesCount > 0)
                                                    {{ $messagesCount }}
                                                @endif
                                            </div>
                                        @else
                                            <div
                                                class="mb-2 bottom-auto bg-green-500 mx-1 rounded-full px-2 py-1 text-center align-baseline text-xs font-bold leading-none text-white">
                                                0
                                            </div>
                                        @endif
                                    @endif
                                </x-nav-link>
                            @endif
                        @endforeach
                    @else
                        <x-nav-link :href="route('admin.entity.index')" :active="request()->routeIs('admin.entity.index')">
                            Сущности
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                        class="inline-block text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg px-6 pb-2 pt-2.5 text-white">
                        Выход
                    </button>
                </form>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @foreach ($menu as $link)
                @if (count($link['sub']) > 0)
                    <div class="flex items-center sm:ms-6">
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ $link['name'] }} </div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @foreach ($link['sub'] as $sub)
                                    <x-dropdown-link :href="route($sub['route'])">
                                        {{ $sub['name'] }}
                                    </x-dropdown-link>
                                @endforeach
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <x-responsive-nav-link :href="route($link['route'])" :active="request()->routeIs($link['routeIs'])">
                        {{ $link['name'] }}
                    </x-responsive-nav-link>
                @endif
            @endforeach
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
