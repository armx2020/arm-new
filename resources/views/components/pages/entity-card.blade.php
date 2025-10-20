<section>
    <div class="flex flex-col mx-auto my-6 lg:my-8">

        <div class="flex flex-col sm:flex-row bg-white rounded-md p-3 lg:p-4 relative h-auto lg:min-h-80">

            <div class="flex sm:hidden pb-4 px-3 w-full justify-end">
                <a href="{{ url()->previous() }}" class="[&>svg]:fill-[#a1b4c2] w-3 h-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                        <path
                            d="M376.6 84.5c11.3-13.6 9.5-33.8-4.1-45.1s-33.8-9.5-45.1 4.1L192 206 56.6 43.5C45.3 29.9 25.1 28.1 11.5 39.4S-3.9 70.9 7.4 84.5L150.3 256 7.4 427.5c-11.3 13.6-9.5 33.8 4.1 45.1s33.8 9.5 45.1-4.1L192 306 327.4 468.5c11.3 13.6 31.5 15.4 45.1 4.1s15.4-31.5 4.1-45.1L233.7 256 376.6 84.5z" />
                    </svg>
                </a>
            </div>


            @php
                $images = $entity->images(true)->get();
                $logo = $entity->logo()->First();
            @endphp

            <div class="flex flex-col gap-2">

                @if ($images->count() > 0)
                    <div class="group relative max-w-full aspect-[16/11] sm:max-w-[320px] md:max-w-[380px] xl:max-w-[430px]"
                        wire:ignore>
                        @if ($logo)
                            <img class="w-24 max-h-24 object-cover rounded-lg absolute z-10 top-3 left-3"
                                src="{{ asset('storage/' . $logo->path) }}">
                        @endif
                        <div class="swiper mySwiper2 w-full h-full">
                            <div class="swiper-wrapper w-full h-full">
                                @foreach ($images as $image)
                                    <div class="swiper-slide flex w-full h-full">
                                        <a data-fancybox="gallery" href="{{ asset('storage/' . $image->path) }}"
                                            class="w-full h-full">
                                            <img class="w-full h-full object-cover rounded-lg"
                                                src="{{ asset('storage/' . $image->path) }}">
                                        </a>
                                    </div>
                                @endforeach
                            </div>

                            <div
                                class="swiper-pagination
                   absolute bottom-2 left-1/2
                   transform -translate-x-1/2
                   !w-[48px] h-6 !left-[50%] !translate-x-[-50%] text-center !text-white
                   bg-black/60 text-xs
                   rounded-full py-1">
                            </div>

                            <div
                                class="swiper-button-prev
                   hidden group-hover:flex
                   items-center
                   absolute inset-y-0 left-2
                   text-white z-10">
                            </div>
                            <div
                                class="swiper-button-next
                   hidden group-hover:flex
                   items-center
                   absolute inset-y-0 right-2
                   text-white z-10">
                            </div>
                        </div>
                    </div>



                    {{-- VIDEO --}}
                    @php
                        $url = $entity->video_url;
                        $isYoutube = Str::contains($url, ['youtube.com', 'youtu.be']);
                        $videoId = $isYoutube
                            ? last(explode('/', parse_url($url, PHP_URL_PATH)))
                            : last(explode('/', trim(parse_url($url, PHP_URL_PATH), '/')));
                    @endphp

                    @if ($entity->video_url)
                        <div class="flex flex-row">
                            <div class="block w-1/3 sm:w-[106px] md:w-[126px] xl:w-[143px] pr-[10px]">
                                <div class="pt-1 h-14 ms:h-16 ls:h-20 sm:h-16 md:h-20">
                                    <a href="{{ $isYoutube ? 'https://youtube.com/watch?v=' . $videoId : $url }}"
                                        target="_blank" rel="noopener noreferrer"
                                        class="block h-14 ms:h-16 ls:h-20 sm:h-16 md:h-20 relative hover:drop-shadow-xl">

                                        <img class="w-full h-full object-cover rounded-lg"
                                            src="{{ url('/image/video_link.jpg') }}">
                                        <p
                                            class="absolute text-white bottom-0 lg:bottom-2 right-3 text-[10px] lg:text-sm">
                                            {{ $isYoutube ? 'YouTube' : 'RuTube' }} Video
                                        </p>
                                    </a>
                                </div>
                            </div>
                            {{-- SLAIDER --}}
                            <div class="swiper mySwiper mt-1 w-2/3 sm:w-[212px] md:w-[252px] xl:w-[286px] h-22">
                                <div class="swiper-wrapper cursor-pointer">

                                    @foreach ($images as $image)
                                        <div class="swiper-slide">
                                            <img class="w-full h-14 ms:h-16 ls:h-20 sm:h-16 md:h-20 object-cover rounded-lg"
                                                src="{{ asset('storage/' . $image->path) }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    @else
                        {{-- SLAIDER --}}
                        <div class="swiper mySwiper mt-1 w-full sm:w-[320px] md:w-[380px] xl:w-[430px] h-22">
                            <div class="swiper-wrapper cursor-pointer">

                                @foreach ($images as $image)
                                    <div class="swiper-slide">
                                        <img class="w-full h-14 ms:h-16 ls:h-20 sm:h-16 md:h-20 object-cover rounded-lg"
                                            src="{{ asset('storage/' . $image->path) }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div
                        class="group relative max-w-full aspect-[16/11] sm:max-w-[320px] md:max-w-[380px] xl:max-w-[430px]">
                        <div class="w-full h-full">
                            <div class="swiper-slide flex w-full h-full">
                                <img class="w-full h-full object-cover rounded-lg"
                                    src="{{ url('/image/no_photo.jpg') }}">
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex justify-between my-3 pl-0 text-xs text-blue-600">

                    <a href="{{ route('entity.photo.edit', ['idOrTranscript' => $entity->id]) }}"
                        class="whitespace-nowrap cursor-pointer flex text-center hover:text-blue-800">
                        Добавить фото
                    </a>

                    <a href="{{ route('entity.edit', ['idOrTranscript' => $entity->id]) }}"
                        class="whitespace-nowrap cursor-pointer flex text-center hover:text-blue-800">
                        Исправить информацию
                    </a>

                </div>

            </div>

            <div class="flex flex-col px-0 lg:px-6 mt-3 sm:mt-0 justify-start break-keep overflow-hidden">
                <h3 class="block text-left text-md font-semibold sm:mx-4">
                    {{ mb_substr($entity->name, 0, 90, 'UTF-8') }}
                </h3>

                @if ($entity->description)
                    <span class="sm:mx-4 text-sm font-semibold mt-4 block">Описание</span>
                    <div class="description-container relative max-w-prose sm:mx-4">
                        <p
                            class="description-content
                   text-base font-normal text-gray-700
                   break-keep whitespace-normal text-justify
                   overflow-hidden transition-all duration-300
                   line-clamp-5">
                            {{ $entity->description }}
                        </p>
                        <div type="button"
                            class="toggle-button absolute bottom-0 right-0 z-10 hidden cursor-pointer text-base focus:outline-none bg-white px-0">
                            <span>...</span>
                            <span class="hover:underline text-blue-600">Показать ещё</span>
                        </div>
                    </div>
                @endif

                @if ($entity->entity_type_id == 1)
                    @if ($entity->fields && count($entity->fields) > 3)
                        <span class="sm:mx-4 text-sm font-semibold mt-4">Деятельность</span>
                        @foreach ($entity->fields as $category)
                            <p class="flex text-left text-sm sm:mx-4 text-gray-500 break-keep">
                                &bull; {{ $category->name }}
                            </p>
                        @endforeach
                    @elseif ($entity->offers && count($entity->offers) > 0)
                        <span class="sm:mx-4 text-sm font-semibold mt-4">Деятельность</span>
                        @foreach ($entity->offers as $offer)
                            <p class="flex text-left text-sm sm:mx-4 text-gray-500 break-keep">
                                &bull; {{ $offer->name }}
                            </p>
                        @endforeach
                    @endif
                @endif

                @if ($entity->city)
                    <div class="flex sm:mx-4 mt-4 max-w-prose">
                        <span class="text-sm">Город:</span>
                        <p class="text-sm text-gray-500 break-keep ml-1">
                            {{ mb_substr($entity->city->name, 0, 400, 'UTF-8') }}
                        </p>
                    </div>
                @endif

                @if ($entity->address)
                    <div class="flex sm:mx-4 mt-4 max-w-prose">
                        <span class="text-sm">Адрес:</span>
                        <p class="text-sm text-gray-500 break-keep ml-1">
                            {{ mb_substr($entity->address, 0, 400, 'UTF-8') }}
                        </p>
                    </div>
                @endif

                @if ($entity->director)
                    <div class="flex sm:mx-4 mt-4 max-w-prose">
                        <span class="text-sm">Руководитель:</span>
                        <a href="{{ mb_substr($entity->director, 0, 400, 'UTF-8') }}"
                            class="text-sm text-gray-500 break-keep ml-1">
                            {{ mb_substr($entity->director, 0, 400, 'UTF-8') }}
                        </a>
                    </div>
                @endif

                @if ($entity->phone)
                    <div class="flex sm:mx-4 mt-4 max-w-prose">
                        <span class="text-sm">Телефон:</span>
                        <p class="masked-data phone text-sm text-gray-500 break-keep ml-1">
                            {{ mb_substr($entity->phone, 0, 400, 'UTF-8') }}
                        </p>
                    </div>
                @endif

                @if ($entity->web)
                    <div class="flex sm:mx-4 mt-4 max-w-prose">
                        <span class="text-sm">Сайт:</span>
                        <a href="{{ mb_substr($entity->web, 0, 400, 'UTF-8') }}"
                            class="text-sm text-gray-500 break-keep ml-1">
                            {{ mb_substr($entity->web, 0, 400, 'UTF-8') }}
                        </a>
                    </div>
                @endif

                <div class="my-3 sm:pl-4">
                    <x-pages.social :entity=$entity :coloric="true" />
                </div>



                @if ($entity->user_id && $entity->user_id !== Auth::user()?->id)
                    <div class="flex flex-col pl-0 sm:pl-4 mt-2">

                        {{-- Ссылка на источник --}}
                        @if ($entity->category_id == 19 && isset($entity->link))
                            <div class="flex max-w-[400px]">
                                <a href="{{ $entity->link }}"
                                    class="whitespace-nowrap text-[clamp(10px, 4vw, 16px)] w-full cursor-pointer inline-block bg-blue-700 hover:bg-blue-800 rounded-lg px-6 pb-2 pt-2.5 mt-1 text-center text-white">
                                    Записаться на приём
                                </a>
                            </div>
                        @endif

                        <div class="flex justify-between max-w-[400px] mt-2">

                            {{-- 
                            TODO - доделать мессенджер
                            <form action="{{ route('messenger') }}" method="POST" class="w-1/2 mr-1">
                                @csrf
                                <input type="hidden" name="entity_id" value="{{ $entity->id }}">
                                <button type="submit"
                                    class="whitespace-nowrap text-[clamp(10px, 4vw, 16px)] w-1/2 cursor-pointer inline-block bg-blue-400 hover:bg-blue-500 rounded-lg px-6 pb-2 pt-2.5 text-center text-white w-full">
                                    Написать
                                </button>
                            </form> --}}

                            @if (isset($entity->phone))
                                <a href="tel:{{ $entity->phone }}"
                                    class="whitespace-nowrap text-[clamp(10px, 4vw, 16px)] w-1/2 cursor-pointer inline-block bg-green-400 hover:bg-green-500 rounded-lg px-6 pb-2 pt-2.5 text-center text-white">
                                    Позвонить
                                </a>
                            @endif
                        </div>

                        @isset($entity->paymant_link)
                            <div class="flex max-w-[400px]">
                                <a href="{{ $entity->paymant_link }}" target="_blank"
                                    class="whitespace-nowrap text-[clamp(10px, 4vw, 16px)] w-full cursor-pointer inline-block bg-yellow-400 hover:bg-yellow-500 rounded-lg px-6 pb-2 pt-2.5 mt-1 text-center w-full">
                                    <p class="flex justify-between px-8 lg:px-12">Оказать помощь
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 pl-2" viewBox="0 0 576 512">
                                            <path
                                                d="M512 80c8.8 0 16 7.2 16 16l0 32L48 128l0-32c0-8.8 7.2-16 16-16l448 0zm16 144l0 192c0 8.8-7.2 16-16 16L64 432c-8.8 0-16-7.2-16-16l0-192 480 0zM64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zm56 304c-13.3 0-24 10.7-24 24s10.7 24 24 24l48 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-48 0zm128 0c-13.3 0-24 10.7-24 24s10.7 24 24 24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-112 0z" />
                                        </svg>
                                    </p>

                                </a>
                            </div>
                        @endisset
                    </div>
                @endif



                @role('super-admin')
                    <div class="flex place-self-end items-end h-full">
                        <a href="{{ route('admin.entity.edit', ['entity' => $entity->id]) }}"
                            class="[&>svg]:fill-[#a1b4c2]">
                            перейти в админ-панель
                        </a>
                    </div>
                @endrole

                <div class="hidden lg:block absolute right-4 w-4 h-4">
                    <a href="{{ $entityTypeUrl }}" class="[&>svg]:fill-[#a1b4c2]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                            <path
                                d="M376.6 84.5c11.3-13.6 9.5-33.8-4.1-45.1s-33.8-9.5-45.1 4.1L192 206 56.6 43.5C45.3 29.9 25.1 28.1 11.5 39.4S-3.9 70.9 7.4 84.5L150.3 256 7.4 427.5c-11.3 13.6-9.5 33.8 4.1 45.1s33.8 9.5 45.1-4.1L192 306 327.4 468.5c11.3 13.6 31.5 15.4 45.1 4.1s15.4-31.5 4.1-45.1L233.7 256 376.6 84.5z" />
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </div>

    @if ($entity->coordinates)
        <div class="flex my-6 lg:my-8">
            <iframe
                src="https://yandex.ru/map-widget/v1/?ll={{ $entity->lon }},{{ $entity->lat }}&lat={{ $entity->lat }}&lon={{ $entity->lon }}&z=11&pt={{ $entity->lon }},{{ $entity->lat }},pm2blm"
                width="100%" height="300" frameborder="0">
            </iframe>
        </div>
    @endif


    @if ($entity->getTable() == 'companies')
        <x-pages.company-offers :$entity />
    @endif
    <script>
        $(document).ready(function() {
            $('.masked-data').each(function() {
                var $element = $(this);
                var fullText = $.trim($element.text());
                var isPhone = $element.hasClass('phone');
                var threshold = isPhone ? 8 : 4;
                if (fullText.length <= threshold) {
                    return;
                }
                var maskedText = fullText.slice(0, threshold) + '********';
                $element.empty();
                var $maskedSpan = $('<span>').addClass('masked-part text-gray-500 mr-2').text(maskedText);
                var $fullSpan = $('<span>').addClass('full-part hidden text-gray-500 mr-2').text(fullText);
                var $button = $('<button>').addClass('show-full text-blue-500 hover:underline text-sm')
                    .text('Показать');
                $element.append($maskedSpan, $fullSpan, $button);
                $button.on('click', function(e) {
                    e.preventDefault();
                    $maskedSpan.addClass('hidden');
                    $fullSpan.removeClass('hidden');
                    $(this).remove();
                });
            });

            $('.description-container').each(function() {
                var $container = $(this);
                var $paragraph = $container.find('.description-content');
                var $toggleBtn = $container.find('.toggle-button');

                if ($paragraph[0].scrollHeight > $paragraph.outerHeight()) {
                    $toggleBtn.removeClass('hidden');
                }

                $toggleBtn.on('click', function() {
                    $paragraph.removeClass('line-clamp-5');
                    $toggleBtn.remove();
                });
            });

            let swiperThumbs = new Swiper(".mySwiper", {
                spaceBetween: 10,
                slidesPerView: {{ $entity->video_url ? 2 : 3 }},
                freeMode: true,
                watchSlidesProgress: true,
            });

            let swiperMain = new Swiper(".mySwiper2", {
                spaceBetween: 10,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                    type: 'fraction',
                    renderFraction: (currentClass, totalClass) => {
                        return '<span class="' + currentClass + '"></span>' +
                            ' / ' +
                            '<span class="' + totalClass + '"></span>';
                    }
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                thumbs: {
                    swiper: swiperThumbs,
                },
            });

            Fancybox.bind("[data-fancybox='gallery']", {
                Thumbs: {
                    autoStart: true,
                },
                animated: false,
                showClass: "fancybox-fadeIn",
                hideClass: "fancybox-fadeOut",
            });

        });
    </script>
    <style>
        .mySwiper .swiper-slide-thumb-active img {
            @apply border-2 border-indigo-600 rounded-lg;
            border: 2px solid #60A5FA;
            transition: transform 0.2s;
        }

        .mySwiper2 .swiper-button-next,
        .mySwiper2 .swiper-button-prev {
            opacity: 0;
            transition: opacity 0.2s;
        }

        .group:hover .swiper-button-next,
        .group:hover .swiper-button-prev {
            opacity: 1;
        }
    </style>
</section>
