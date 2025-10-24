<nav
    class="flex-no-wrap relative flex w-full items-center justify-between bg-white lg:flex-nowrap lg:justify-start p-4 lg:py-5 text-base h-[50px] xl:min-h-[82px]">
    <div class="flex w-full lg:w-10/12 max-w-7xl items-center justify-between mx-auto text-base font-medium">
        <div class="block xl:hidden">
            @if (isset($regionName) && $regionName !== 'Россия')
                <a class="" href="{{ route('home', ['regionTranslit' => $region]) }}">
                    <img src="{{ url('/image/logo.png') }}" class="h-7" alt="logo" />
                </a>
            @else
                <a class="" href="{{ route('home') }}">
                    <img src="{{ url('/image/logo.png') }}" class="h-7" alt="logo" />
                </a>
            @endif
        </div>
        <div class="block px-0 md:px-2 xl:hidden">
            <button class="text-blue-600 text-sm hover:text-blue-400 locationButton" id="locationButton">
                <img src="{{ url('/image/location-marker.png') }}" class="w-4 h-4 inline" />
                @if ($regionName)
                    {{ mb_substr($regionName, 0, 19, 'UTF-8') }}
                @else
                    <p class="truncate">
                        Вся Россия
                    </p>
                @endif
            </button>
        </div>
        <button class="block px-1 md:px-2 xl:hidden" type="button" id="openMenu">
            <span class="[&>svg]:w-8 [&>svg]:fill-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8">
                    <path fill-rule="evenodd"
                        d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z"
                        clip-rule="evenodd" />
                </svg>
            </span>
        </button>
        <div class="bg-white w-full min-h-screen absolute top-0 right-0 z-40 hidden" id="menu">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" id="closeMenu"
                class="w-6 h-6 absolute right-4 top-3">
                <g>
                    <path d="M21 21L12 12M12 12L3 3M12 12L21.0001 3M12 12L3 21.0001" stroke="#000000" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </g>
            </svg>
            <ul class="list-style-none mx-auto flex flex-col pl-0 text-lg mt-10">
                <li class="mb-4 my-2 p-1 flex border justify-start">
                    @auth
                        <div class="flex flex-row items-center">
                            <a class="mx-4 text-md" href="{{ route('dashboard') }}">{{ Auth::user()->firstname }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="inline-block bg-blue-500 rounded-lg px-3 pb-1 pt-[0.3rem] text-white"
                                    href="{{ route('register') }}">
                                    Выход
                                </button>
                            </form>
                        </div>
                    @endauth

                    @guest
                        <div class="flex flex-row items-center">
                            <a class="mx-4 ml-3" href="{{ route('login') }}">Войти</a>
                            <a class="mx-4" href="{{ route('register') }}">
                                Регистрация
                            </a>
                        </div>
                    @endguest
                </li>

                 @if(app()->environment(['local', 'development']))
                    <li class="mb-4 mx-4">
                        <a href="{{ route('admin.diagnostics') }}" 
                           class="block bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold px-4 py-3 rounded-lg shadow-lg text-center">
                            🔧 ДИАГНОСТИКА (DEV ONLY)
                        </a>
                    </li>
                @endif
                
                 @foreach ($headerMenu as $item)
                    <li class="mb-2 pl-4">
                        <a href="{{ $item['url'] }}"
                            class="hover:text-gray-400 {{ $item['is_active'] ? 'text-gray-500' : '' }}">
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endforeach

                <li class="mb-2">
                    <div class="flex flex-row items-start justify-start my-4 pl-2">
                        <a class="mx-2" href="https://t.me/+79786502200">
                            <svg width="37" height="37" viewBox="0 0 513 512" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="256" cy="256" r="256" fill="white" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M230.658 1.05638C121.151 12.6294 30.8985 92.9454 6.90747 200.17C-0.00252771 231.052 -0.908528 268.444 4.49547 299.67C21.4915 397.874 95.1795 478.282 191.25 503.457C226.698 512.746 265.442 514.502 300.646 508.417C400.022 491.24 480.881 416.166 505.235 318.467C511.142 294.773 512.421 283.647 512.421 255.967C512.421 228.287 511.142 217.161 505.235 193.467C480.867 95.7154 399.615 20.3454 300.443 3.50338C280.012 0.03438 250.217 -1.01062 230.658 1.05638ZM370.734 156.648C375.471 160.163 376.343 164.723 374.998 178.927C372.153 208.978 350.732 342.042 345.303 363.396C343.288 371.323 339.725 378.131 335.915 381.337C333.204 383.618 331.879 383.967 325.92 383.967C319.926 383.967 318.12 383.485 311.747 380.186C307.73 378.106 301.968 374.593 298.943 372.379C295.918 370.165 278.818 358.537 260.943 346.538C227.046 323.786 220.69 318.761 218.884 313.287C216.572 306.281 220.259 301.519 248.176 275.46C274.861 250.55 304.456 221.64 306.564 218.423C308.687 215.183 306.911 211.967 302.998 211.967C300.885 211.967 284.668 222.232 241.783 250.717C205.313 274.941 181.381 290.204 177.943 291.433C170.012 294.268 160.166 293.856 145.746 290.087C132.159 286.535 109.79 279.399 105.443 277.23C97.1555 273.094 95.5925 267.593 101.193 262.271C105.529 258.15 105.563 258.134 162.146 233.593C276.197 184.125 332.555 160.961 352.331 155.424C359.099 153.53 367.263 154.072 370.734 156.648Z"
                                    fill="#24A1DE" />
                            </svg>
                        </a>
                        <a class="mx-2" href="https://wa.me/+79786502200">
                            <svg width="37" height="37" viewBox="0 0 506 506" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="253" cy="253" r="253" fill="white" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M220.524 1.48385C165.036 8.58985 113.924 33.8069 73.9161 73.8148C33.4291 114.302 8.45013 165.355 1.49613 221.829C-0.252875 236.028 -0.522875 265.859 0.975125 279.359C9.58012 356.918 52.3531 425.079 118.893 467.269C162.113 494.672 217.368 508.658 268.221 505.066C304.429 502.509 333.012 494.875 364.46 479.361C414.841 454.508 454.774 414.57 479.448 364.359C495.054 332.6 502.61 304.326 505.167 268.12C508.921 214.966 493.874 158.558 463.924 113.515C421.633 49.9118 355.064 9.26185 279.46 0.873848C266.82 -0.528152 233.547 -0.184152 220.524 1.48385ZM283.105 118.244C295.964 121.219 310.897 126.96 320.697 132.696C374.374 164.114 400.403 225.294 385.467 284.934C379.27 309.675 366.587 331.31 347.54 349.626C336.163 360.567 325.714 367.93 312.46 374.345C294.268 383.151 279.443 386.776 258.441 387.555C236.298 388.377 219.477 385.376 200.134 377.154L191.808 373.614L160.879 381.737C143.868 386.204 128.187 389.859 126.032 389.859C120.51 389.859 115.96 385.306 115.96 379.78C115.96 377.622 119.56 362.807 123.96 346.859C128.36 330.911 131.96 317.025 131.96 316.002C131.96 314.978 130.429 310.815 128.557 306.75C125.019 299.064 121.039 286.673 118.796 276.359C117.002 268.11 117.032 235.528 118.842 226.359C130.557 166.999 178.24 122.98 238.96 115.47C240.885 115.232 249.21 115.205 257.46 115.409C268.827 115.691 275.038 116.377 283.105 118.244ZM232.29 139.285C202.734 144.877 175.136 163.128 158.665 187.976C145.271 208.182 139.608 226.978 139.572 251.359C139.541 271.505 142.947 285.856 152.086 304.087C154.217 308.337 155.96 312.636 155.96 313.64C155.96 314.644 153.053 325.997 149.5 338.869C145.947 351.741 143.201 362.434 143.399 362.631C143.597 362.829 154.16 360.261 166.872 356.925C179.585 353.589 191.568 350.859 193.502 350.859C195.435 350.859 199.742 352.235 203.073 353.916C210.842 357.838 224.395 362.245 233.96 363.959C243.163 365.608 264.189 365.591 272.916 363.927C314.228 356.05 347.828 327.079 361.136 287.859C369.072 264.471 368.983 237.394 360.893 214.238C354.739 196.622 345.878 182.934 331.794 169.281C321.823 159.616 313.906 154.127 301.36 148.185C286.174 140.991 275.471 138.651 255.96 138.259C244.933 138.038 237.082 138.378 232.29 139.285ZM221.339 192.109C224.628 195.08 235.318 221.298 234.526 224.451C234.211 225.708 231.705 229.537 228.956 232.961C226.208 236.385 223.96 239.717 223.96 240.366C223.96 242.518 234.227 256.269 240.275 262.217C247.037 268.867 256.554 275.281 265.69 279.344C273.233 282.698 273.931 282.395 281.46 272.493C284.21 268.876 287.491 265.626 288.752 265.269C290.436 264.793 294.945 266.576 305.752 271.99L320.46 279.359L320.765 283.549C321.487 293.485 315.948 302.01 305.25 307.427C299.949 310.111 298.615 310.353 289.46 310.284C280.753 310.219 278.297 309.784 270.46 306.918C250.235 299.524 236.912 290.773 221.437 274.72C216.474 269.571 209.669 261.534 206.314 256.859C191.981 236.882 188.294 222.851 193.437 207.859C195.522 201.784 201.724 193.749 205.98 191.611C211.04 189.068 218.231 189.301 221.339 192.109Z"
                                    fill="#4BAE4F" />
                            </svg>
                        </a>
                        <a class="ml-2" href="mailto:vsearmru@gmail.com">
                            <svg width="37" height="37" viewBox="0 0 512 513" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <ellipse cx="255.68" cy="255.75" rx="255.68" ry="255.75"
                                    fill="white" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M229.224 1.64097C156.922 9.88497 93.7528 45.76 50.5457 103.117C24.3757 137.857 8.19875 177.319 1.35375 223.117C-0.45125 235.192 -0.45125 278.042 1.35375 290.117C9.97575 347.802 33.8188 396.291 73.2398 436.311C111.955 475.614 159.944 500.355 215.509 509.656C239.377 513.652 274.102 513.407 299.212 509.067C398.81 491.851 479.529 416.754 503.921 318.617C508.884 298.646 510.534 286.298 511.199 264.117C511.982 238.028 509.895 217.933 503.93 194.117C487.771 129.601 445.802 72.671 388.863 38.03C358.563 19.595 329.255 8.92397 292.806 3.05597C281.275 1.19897 240.963 0.301966 229.224 1.64097ZM370.503 152.709C380.015 154.749 388.81 164.137 390.09 173.617L390.698 178.117L324.031 210.617C287.365 228.492 256.769 243.254 256.041 243.42C255.312 243.587 224.474 228.601 187.511 210.118L120.306 176.513L120.991 172.861C122.27 166.044 125.713 161.031 132.815 155.648C137.829 151.847 140.698 151.754 254.71 151.684C331.201 151.638 366.984 151.954 370.503 152.709ZM188.145 235.617C224.823 253.767 255.322 268.613 255.921 268.609C256.519 268.605 286.934 253.977 323.509 236.104L390.009 203.608L390.3 269.862C390.46 306.302 390.329 337.971 390.008 340.237C388.725 349.309 379.831 358.524 370.409 360.545C363.368 362.055 147.562 362.036 140.515 360.525C134.14 359.158 127.967 354.528 124.03 348.162L121.009 343.275L120.733 272.946C120.581 234.265 120.682 202.617 120.957 202.617C121.232 202.617 151.467 217.467 188.145 235.617Z"
                                    fill="#2196F3" />
                            </svg>
                        </a>
                    </div>
                </li>
            </ul>
        </div>

        <div class="hidden flex-grow items-center xl:flex pr-3">
            @if (isset($regionName) && $regionName !== 'Россия')
                <a class="" href="{{ route('home', ['regionTranslit' => $region]) }}">
                    <img src="{{ url('/image/logo.png') }}" class="w-30 h-10" alt="logo" />
                </a>
            @else
                <a class="" href="{{ route('home') }}">
                    <img src="{{ url('/image/logo.png') }}" class="w-30 h-10" alt="logo" />
                </a>
            @endif
            
            @if(app()->environment(['local', 'development']))
                <a href="{{ route('admin.diagnostics') }}" 
                   class="ml-4 bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold px-4 py-2 rounded-lg shadow-lg hover:from-red-600 hover:to-orange-600 transform hover:scale-105 transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    🔧 ДИАГНОСТИКА
                </a>
            @endif
        </div>
        <div class="visible hidden text-base flex-grow basis-full items-center xl:!flex xl:basis-auto">
            <ul class="list-style-none flex flex-col pl-0 lg:flex-row">
                @foreach ($headerMenu as $item)
                    <li class="mb-4 lg:mb-0 lg:pr-4">
                        <a href="{{ $item['url'] }}"
                            class="hover:text-gray-400 {{ $item['is_active'] ? 'text-gray-500' : '' }}">
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="hidden lg:basis-1/3 xl:flex basis-1/4 items-center justify-between">
            <div class="block">
                <button class="text-blue-600 hover:text-blue-400 block locationButton" id="locationButton">
                    <img src="{{ url('/image/location-marker.png') }}" class="w-4 h-4 inline align-middle" />
                    @isset($regionName)
                        {{ preg_replace('/\([^)]+\)/', '', $regionName) }}
                    @else
                        <p class="truncate">
                            Вся Россия
                        </p>
                    @endisset
                </button>
            </div>

            @auth
                <div class="flex flex-row items-center">
                    <a class="mx-6 text-md" href="{{ route('dashboard') }}">{{ Auth::user()->firstname }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-block bg-blue-500 rounded-lg px-6 pb-2 pt-2.5 text-white"
                            href="{{ route('register') }}">
                            Выход
                        </button>
                    </form>
                </div>
            @endauth

            @guest
                <div class="flex flex-row items-center">
                    <a class="mx-4" href="{{ route('login') }}">Войти</a>
                    <a class="inline-block bg-blue-500 rounded-lg px-6 pb-2 pt-2.5 text-white"
                        href="{{ route('register') }}">
                        Регистрация
                    </a>
                </div>
            @endguest

        </div>
    </div>
</nav>

<div id="location_form" class="hidden fixed inset-0 px-4 min-h-full overlow-hidden sm:px-0 z-50" focusable>

    <div
        class="my-5 mx-auto opacity-100 translate-y-0 sm:scale-100 bg-white rounded-lg overflow-auto shadow-xl transform transition-all sm:w-11/12 lg:w-10/12 h-5/6">

        <div class="m-7">
            <x-secondary-button class="location-close absolute right-4 top-4">
                {{ __('Закрыть') }}
            </x-secondary-button>
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                <div class="px-1 hover:text-gray-500" id="div_all_regions">
                    <img src="{{ url('/image/russian-flag.png') }}" class="w-6 h-6 inline align-middle pb-1" />
                    <a href="{{ route('home') }}">
                        Вся Россия
                    </a>
                </div>
                <div class="px-1 hover:text-gray-500 hidden" id="div_regions_russia">
                    <img src="{{ url('/image/russian-flag.png') }}" class="w-6 h-6 inline align-middle mr-1 pb-1" />
                    <button id="regions_russia">
                        Россия
                    </button>
                </div>
                <div class="px-1 hover:text-gray-500" id="div_all_countries">
                    <img src="{{ url('/image/world.png') }}" class="w-6 h-6 inline align-middle mr-1" />
                    <button id="all_countries">
                        Другие страны
                    </button>
                </div>
            </div>
            <hr class="my-4">
            <div id="regions">
                @foreach ($regions as $letter => $letterCities)
                    <h3 class="text-xl font-bold my-2">{{ $letter }}</h3>
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        @foreach ($letterCities as $region)
                            <div class="px-1 hover:text-gray-500">
                                @if (Route::is('home'))
                                    <a href="{{ route('home', ['regionTranslit' => $region->transcription]) }}">
                                        {{ $region->name }}
                                    </a>
                                @elseif (Route::is('companies') || Route::is('companies.region'))
                                    <a
                                        href="{{ route('companies.region', ['regionTranslit' => $region->transcription]) }}">
                                        {{ $region->name }}
                                    </a>
                                @elseif (Route::is('groups') || Route::is('groups.region'))
                                    <a
                                        href="{{ route('groups.region', ['regionTranslit' => $region->transcription]) }}">
                                        {{ $region->name }}
                                    </a>
                                @elseif (Route::is('places') || Route::is('places.region'))
                                    <a
                                        href="{{ route('places.region', ['regionTranslit' => $region->transcription]) }}">
                                        {{ $region->name }}
                                    </a>
                                @elseif (Route::is('communities') || Route::is('communities.region'))
                                    <a
                                        href="{{ route('communities.region', ['regionTranslit' => $region->transcription]) }}">
                                        {{ $region->name }}
                                    </a>
                                @else
                                    <a href="{{ route('home', ['regionTranslit' => $region->transcription]) }}">
                                        {{ $region->name }}
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <hr class="my-4">
                @endforeach
            </div>
            <div id="countries" class="hidden">
                @foreach ($countries as $letter => $letterCities)
                    <h3 class="text-xl font-bold my-2">{{ $letter }}</h3>
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        @foreach ($letterCities as $region)
                            <div class="px-1 hover:text-gray-500">
                                @if (Route::is('home'))
                                    <a href="{{ route('home', ['regionTranslit' => $region->code]) }}">
                                        {{ $region->name_ru }}
                                    </a>
                                @elseif (Route::is('companies') || Route::is('companies.region'))
                                    <a href="{{ route('companies.region', ['regionTranslit' => $region->code]) }}">
                                        {{ $region->name_ru }}
                                    </a>
                                @elseif (Route::is('groups') || Route::is('groups.region'))
                                    <a href="{{ route('groups.region', ['regionTranslit' => $region->code]) }}">
                                        {{ $region->name_ru }}
                                    </a>
                                @elseif (Route::is('places') || Route::is('places.region'))
                                    <a href="{{ route('places.region', ['regionTranslit' => $region->code]) }}">
                                        {{ $region->name_ru }}
                                    </a>
                                @elseif (Route::is('communities') || Route::is('communities.region'))
                                    <a href="{{ route('communities.region', ['regionTranslit' => $region->code]) }}">
                                        {{ $region->name_ru }}
                                    </a>
                                @else
                                    <a href="{{ route('home', ['regionTranslit' => $region->code]) }}">
                                        {{ $region->name_ru }}
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <hr class="my-4">
                @endforeach
            </div>
        </div>
    </div>
</div>

<script type='text/javascript'>
    $(document).ready(function() {
        $(".locationButton").click(function() {
            $("#location_form").toggle();
            $('body, html').css('overflow', 'hidden')
        });
        $(".location-close").click(function() {
            $("#location_form").toggle();
            $('body, html').css('overflow', 'visible')
        });
        $("#all_countries").click(function() {
            $("#regions").toggle();
            $("#countries").toggle();
            $("#div_all_regions").toggle();
            $("#div_regions_russia").toggle();
        });
        $("#regions_russia").click(function() {
            $("#regions").toggle();
            $("#countries").toggle();
            $("#div_all_regions").toggle();
            $("#div_regions_russia").toggle();
        });
    });
</script>
