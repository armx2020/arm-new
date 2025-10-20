@extends('layouts.app')

@php
    $sitemap = App\Models\SiteMap::select('url', 'title', 'description')
        ->where('url', url()->current())
        ->First();

    $title = 'Армянский справочник для армян России и мира';
    $description = 'Армянский справочник для армян России и мира';

    if ($sitemap) {
        $title = $sitemap->title;
        $description = $sitemap->description;
    }

@endphp

@section('title')
    <title>{{ $title }}
    </title>
@endsection

@section('meta')
    <meta name="robots" content="index, follow" />
    <meta name="description" content="{{ $description }}">
@endsection

@section('content')
    {{--  Хлебные крошки --}}
    <nav class="hidden md:block mb-2 mt-3 lg:mt-5 rounded-md mx-auto text-xs sm:text-sm md:text-md px-1">

        @php
            $homeUrl = route('home');

            if ($region && $region !== 'russia') {
                $homeUrl = route('home', ['regionTranslit' => $region]);
            }
        @endphp

        <ol class="list-reset flex flex-nowrap overflow-hidden">
            <li class="text-neutral-500">
                <a href="{{ $homeUrl }}" class="truncate">
                    Главная
                </a>
            </li>
            <li>
                <a href="{{ $homeUrl }}">
                    <span class="mx-2 text-neutral-500">/</span>
                </a>
            </li>
            <li class="text-neutral-500">
                <a href="{{ $user->firstname }}" class="truncate">
                    {{ $user->firstname }}
                </a>
            </li>

        </ol>
    </nav>

    <section>
        <div class="flex flex-col mx-auto my-6 lg:my-8">
            <div class="flex flex-col md:flex-row basis-full bg-white rounded-md p-2 lg:p-10 relative">
                <div class="flex flex-col basis-1/5">
                    @if ($user->image)
                        <img class="h-40 lg:h-48 w-40 lg:w-48 rounded-full mx-auto p-1 flex object-cover"
                            src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}">
                    @else
                        <img class="h-40 lg:h-48 w-40 lg:w-48 rounded-full mx-auto p-1 flex object-cover"
                            src="{{ url('/image/no-image.png') }}" alt="image" />
                    @endif
                    <div class="m-5">
                        <div class="my-2 flex flex-row">
                            <div class="basis-4/5 text-left text-sm">Заполненость профиля</div>
                            <div class="basis-1/5 text-right text-sm">{{ $fullness }}%</div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-md mb-5">
                            <div class="bg-green-500 h-2 text-gray-50 align-middle p-0.5 text-center text-md font-medium leading-none text-primary-100"
                                style='width: {{ $fullness }}%'></div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col px-3 lg:px-10">
                    <h3 class="text-left text-xl lg:text-2xl mx-4">{{ $user->firstname }} {{ $user->lastname }}</h3>
                    <p class="text-left text-md mx-4 my-1 text-gray-600">{{ $user->city->name }}</p>

                    <hr class="mt-3 mb-3">
                    <div class="flow-root mb-3 break-words">

                        @if ($user->phone)
                            <div class="inline m-2">
                                <svg class="w-5 h-5 inline my-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M9.20711 14.7929C11.5251 17.111 13.6781 18.4033 15.2802 19.121C16.6787 19.7475 18.3296 19.2562 19.6167 17.9691L19.9114 17.6744L16.3752 15.4241C15.7026 16.4048 14.4319 16.979 13.1632 16.4434C12.2017 16.0376 10.9302 15.3445 9.7929 14.2071C8.65557 13.0698 7.96243 11.7983 7.55659 10.8369C7.02105 9.56817 7.59528 8.29741 8.57591 7.62479L6.32562 4.08863L6.0309 4.38335C4.74381 5.67044 4.25256 7.32131 4.87905 8.71986C5.59671 10.322 6.88908 12.4749 9.20711 14.7929ZM14.4626 20.9462C12.6479 20.1334 10.2905 18.7047 7.7929 16.2071C5.29532 13.7096 3.86668 11.3521 3.05381 9.53748C1.9781 7.13609 2.95955 4.62627 4.61669 2.96913L4.91141 2.67441C5.81615 1.76967 7.32602 1.93541 8.01294 3.01488L10.8437 7.46315C10.9957 7.70201 11.0393 7.99411 10.9637 8.26696C10.8881 8.53981 10.7005 8.76784 10.4472 8.89446L9.81354 9.2113C9.38171 9.42721 9.2931 9.80786 9.39916 10.0591C9.73804 10.8619 10.3046 11.8904 11.2071 12.7929C12.1097 13.6955 13.1381 14.262 13.9409 14.6009C14.1922 14.7069 14.5728 14.6183 14.7887 14.1865L15.1056 13.5528C15.2322 13.2996 15.4602 13.1119 15.7331 13.0363C16.0059 12.9607 16.298 13.0044 16.5369 13.1564L20.9852 15.9871C22.0646 16.674 22.2304 18.1839 21.3256 19.0886L21.0309 19.3833C19.3738 21.0405 16.8639 22.0219 14.4626 20.9462Z"
                                        fill="#000000" />
                                </svg>
                                {{ $user->phone }}
                            </div>
                        @endif

                        @if ($user->web)
                            <div class="inline m-2">
                                <svg class="w-5 h-5 inline my-2" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"
                                    id="Layer_1" viewBox="0 0 128 128">
                                    <defs>
                                        <style>
                                            .cls-1 {
                                                fill: #062b31;
                                            }

                                            .cls-2 {
                                                fill: none;
                                                stroke: #062b31;
                                                stroke-miterlimit: 10;
                                                stroke-width: 6.5px;
                                            }
                                        </style>
                                    </defs>
                                    <title />
                                    <path class="cls-1"
                                        d="M64,16A47.5,47.5,0,1,1,16.5,63.5,47.55,47.55,0,0,1,64,16m0-6.5a54,54,0,1,0,54,54,54,54,0,0,0-54-54Z" />
                                    <path class="cls-1"
                                        d="M65.08,16c2.09,0,5.78,3.66,8.93,11.69,3.71,9.46,5.75,22.18,5.75,35.81s-2,26.35-5.75,35.81c-3.15,8-6.83,11.69-8.93,11.69s-5.78-3.66-8.93-11.69C52.45,89.85,50.4,77.13,50.4,63.5s2-26.35,5.75-35.81C59.31,19.65,63,16,65.08,16m0-6.5c-11.7,0-21.18,24.18-21.18,54s9.48,54,21.18,54,21.18-24.18,21.18-54-9.48-54-21.18-54Z" />
                                    <line class="cls-2" x1="17.66" x2="112.5" y1="80.37" y2="80.37" />
                                    <line class="cls-2" x1="17.66" x2="112.5" y1="46.62" y2="46.62" />
                                </svg>
                                {{ $user->web }}
                            </div>
                        @endif

                        @if ($user->whatsapp)
                            <div class="inline m-2">
                                <svg class="w-5 h-5 inline my-2" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" height="56.693px" id="Layer_1"
                                    style="enable-background:new 0 0 56.693 56.693;" version="1.1"
                                    viewBox="0 0 56.693 56.693" width="56.693px" xml:space="preserve">
                                    <style type="text/css">
                                        .st0 {
                                            fill-rule: evenodd;
                                            clip-rule: evenodd;
                                        }
                                    </style>
                                    <g>
                                        <path class="st0"
                                            d="M46.3802,10.7138c-4.6512-4.6565-10.8365-7.222-17.4266-7.2247c-13.5785,0-24.63,11.0506-24.6353,24.6333   c-0.0019,4.342,1.1325,8.58,3.2884,12.3159l-3.495,12.7657l13.0595-3.4257c3.5982,1.9626,7.6495,2.9971,11.7726,2.9985h0.01   c0.0008,0-0.0006,0,0.0002,0c13.5771,0,24.6293-11.0517,24.635-24.6347C53.5914,21.5595,51.0313,15.3701,46.3802,10.7138z    M28.9537,48.6163h-0.0083c-3.674-0.0014-7.2777-0.9886-10.4215-2.8541l-0.7476-0.4437l-7.7497,2.0328l2.0686-7.5558   l-0.4869-0.7748c-2.0496-3.26-3.1321-7.028-3.1305-10.8969c0.0044-11.2894,9.19-20.474,20.4842-20.474   c5.469,0.0017,10.6101,2.1344,14.476,6.0047c3.8658,3.8703,5.9936,9.0148,5.9914,14.4859   C49.4248,39.4307,40.2395,48.6163,28.9537,48.6163z" />
                                        <path class="st0"
                                            d="M40.1851,33.281c-0.6155-0.3081-3.6419-1.797-4.2061-2.0026c-0.5642-0.2054-0.9746-0.3081-1.3849,0.3081   c-0.4103,0.6161-1.59,2.0027-1.9491,2.4136c-0.359,0.4106-0.7182,0.4623-1.3336,0.1539c-0.6155-0.3081-2.5989-0.958-4.95-3.0551   c-1.83-1.6323-3.0653-3.6479-3.4245-4.2643c-0.359-0.6161-0.0382-0.9492,0.27-1.2562c0.2769-0.2759,0.6156-0.7189,0.9234-1.0784   c0.3077-0.3593,0.4103-0.6163,0.6155-1.0268c0.2052-0.4109,0.1027-0.7704-0.0513-1.0784   c-0.1539-0.3081-1.3849-3.3379-1.8978-4.5706c-0.4998-1.2001-1.0072-1.0375-1.3851-1.0566   c-0.3585-0.0179-0.7694-0.0216-1.1797-0.0216s-1.0773,0.1541-1.6414,0.7702c-0.5642,0.6163-2.1545,2.1056-2.1545,5.1351   c0,3.0299,2.2057,5.9569,2.5135,6.3676c0.3077,0.411,4.3405,6.6282,10.5153,9.2945c1.4686,0.6343,2.6152,1.013,3.5091,1.2966   c1.4746,0.4686,2.8165,0.4024,3.8771,0.2439c1.1827-0.1767,3.6419-1.489,4.1548-2.9267c0.513-1.438,0.513-2.6706,0.359-2.9272   C41.211,33.7433,40.8006,33.5892,40.1851,33.281z" />
                                    </g>
                                </svg>
                                {{ $user->whatsapp }}
                            </div>
                        @endif

                        @if ($user->instagram)
                            <div class="inline m-2">
                                <svg class="w-5 h-5 inline my-2" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px"
                                    viewBox="0 0 56.7 56.7" enable-background="new 0 0 56.7 56.7" xml:space="preserve">
                                    <g>
                                        <path
                                            d="M28.2,16.7c-7,0-12.8,5.7-12.8,12.8s5.7,12.8,12.8,12.8S41,36.5,41,29.5S35.2,16.7,28.2,16.7z M28.2,37.7   c-4.5,0-8.2-3.7-8.2-8.2s3.7-8.2,8.2-8.2s8.2,3.7,8.2,8.2S32.7,37.7,28.2,37.7z" />
                                        <circle cx="41.5" cy="16.4" r="2.9" />
                                        <path
                                            d="M49,8.9c-2.6-2.7-6.3-4.1-10.5-4.1H17.9c-8.7,0-14.5,5.8-14.5,14.5v20.5c0,4.3,1.4,8,4.2,10.7c2.7,2.6,6.3,3.9,10.4,3.9   h20.4c4.3,0,7.9-1.4,10.5-3.9c2.7-2.6,4.1-6.3,4.1-10.6V19.3C53,15.1,51.6,11.5,49,8.9z M48.6,39.9c0,3.1-1.1,5.6-2.9,7.3   s-4.3,2.6-7.3,2.6H18c-3,0-5.5-0.9-7.3-2.6C8.9,45.4,8,42.9,8,39.8V19.3c0-3,0.9-5.5,2.7-7.3c1.7-1.7,4.3-2.6,7.3-2.6h20.6   c3,0,5.5,0.9,7.3,2.7c1.7,1.8,2.7,4.3,2.7,7.2V39.9L48.6,39.9z" />
                                    </g>
                                </svg>
                                {{ $user->instagram }}
                            </div>
                        @endif

                        @if ($user->vkontakte)
                            <div class="inline m-2">
                                <svg class="w-6 h-6 inline my-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                                    <title />
                                    <g data-name="vk vkontakte media social" id="vk_vkontakte_media_social">
                                        <path
                                            d="M28.89,22a30.07,30.07,0,0,0-4.13-5.15.2.2,0,0,1,0-.25,40.66,40.66,0,0,0,3.55-5.81,1.9,1.9,0,0,0-.08-1.86A1.81,1.81,0,0,0,26.65,8h-3a2,2,0,0,0-1.79,1.19,35,35,0,0,1-3.12,5.51V9.8A1.79,1.79,0,0,0,16.94,8H12.56a1.4,1.4,0,0,0-1.12,2.21l.4.56a1.84,1.84,0,0,1,.33,1.05v3.84A26.11,26.11,0,0,1,9.05,9.35,2,2,0,0,0,7.16,8H4.71a1.73,1.73,0,0,0-1.66,2.14c1.35,5.73,4.18,10.48,7.77,13a1,1,0,0,0,1.39-.23,1,1,0,0,0-.23-1.4C8.84,19.31,6.34,15.12,5.07,10l2.1,0a26.12,26.12,0,0,0,4.1,7.75,1.6,1.6,0,0,0,1.8.52,1.64,1.64,0,0,0,1.1-1.57V11.82A3.78,3.78,0,0,0,13.71,10h3v5.43A1.77,1.77,0,0,0,18,17.15a1.74,1.74,0,0,0,2-.69A36.87,36.87,0,0,0,23.62,10h2.8a39.81,39.81,0,0,1-3.29,5.37,2.17,2.17,0,0,0,.2,2.83A32.08,32.08,0,0,1,27.25,23H23.9a14,14,0,0,0-4.07-4.31,1.64,1.64,0,0,0-1.73-.13,1.69,1.69,0,0,0-.92,1.52v2.38a.53.53,0,0,1-.5.55h-.86a1,1,0,0,0,0,2h.86a2.52,2.52,0,0,0,2.5-2.55V20.69a11.78,11.78,0,0,1,3,3.32,2,2,0,0,0,1.69,1h3.38a1.92,1.92,0,0,0,1.69-1A2,2,0,0,0,28.89,22Z" />
                                    </g>
                                </svg>
                                {{ $user->vkontakte }}
                            </div>
                        @endif

                        @if ($user->telegram)
                            <div class="inline m-2">
                                <svg class="w-6 h-6 inline my-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g>
                                        <path d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-3.11-8.83l-2.498-.779c-.54-.165-.543-.537.121-.804l9.733-3.76c.565-.23.885.061.702.79l-1.657 7.82c-.116.557-.451.69-.916.433l-2.551-1.888-1.189 1.148c-.122.118-.221.219-.409.244-.187.026-.341-.03-.454-.34l-.87-2.871-.012.008z"
                                            fill-rule="nonzero" />
                                    </g>
                                </svg>
                                {{ $user->telegram }}
                            </div>
                        @endif

                    </div>

                    @if ($user->id !== Auth::user()->id)
                        <div class="flex flex-col xl:flex-row pl-0">
                            <div class="flex space-x-2 max-w-[400px]">

                                {{-- 
                                TODO - доделать мессенджер
                                <form action="{{ route('messenger') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <button type="submit"
                                        class="whitespace-nowrap text-[clamp(10px, 4vw, 16px)] w-1/2 cursor-pointer inline-block bg-blue-400 hover:bg-blue-500 rounded-lg px-6 pb-2 pt-2.5 mt-1 text-center text-white w-full">
                                        Написать
                                    </button>
                                </form> --}}
                                @if (isset($user->phone))
                                    <a href="tel:{{ $user->phone }}"
                                        class="whitespace-nowrap text-[clamp(10px, 4vw, 16px)] w-full cursor-pointer inline-block bg-green-400 hover:bg-green-500 rounded-lg px-6 pb-2 pt-2.5 mt-1 text-center text-white">
                                        Позвонить
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="absolute right-1 top-1 lg:right-3 lg:top-3">
                        <div class="m-3 break-all text-base text-right ">
                            <p class="text-left text-md mx-4 my-1 text-blue-500 drop-shadow-sm">
                                {{ $user->whenVisited() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
