@php
    $typies = [
        'success' => 'green',
        'alert' => 'yellow',
    ];
@endphp

<div>
    @foreach ($typies as $type => $color)
        @if (session($type))
            <div class="mb-4 rounded-lg bg-{{ $color }}-100 px-6 py-5 text-base text-{{ $color }}-700"
                role="alert">
                {{ session($type) }}
            </div>
        @endif
    @endforeach
</div>
