@if (count($entity->offers) > 0)
    <div class="flex flex-col basis-full my-5">
        <div class="w-full text-left p-4 mt-8">
            <div class="flex items-center text-left justify-left">
                <h3 class="text-2xl font-normal">Товары компании</h3>
            </div>
        </div>
        <hr class="w-full mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-3 lg:gap-5">
            @foreach ($entity->offers as $offer)
                <div class="block rounded-lg bg-white h-80">
                    @if ($offer->image == null)
                        <img class="h-48 w-full rounded-2xl p-2 flex object-cover" src="{{ url('/image/no-image.png') }}"
                            alt="image" />
                    @else
                        <img class="h-48 w-full rounded-2xl p-2 flex object-cover"
                            src="{{ asset('storage/' . $offer->image) }}" alt="image">
                    @endif
                    <div class="px-6">
                        <div class="h-12">
                            <h5 class="mb-3 break-words text-lg font-medium leading-tight text-neutral-800">
                                {{ $offer->name }}
                            </h5>
                        </div>
                        <hr class="my-2">
                        <div>
                            <p class="text-right font-bold pb-0">
                                {{ $offer->price }} {{ $offer->unit_of_price }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
