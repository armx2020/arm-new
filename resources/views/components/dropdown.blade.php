@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'origin-top-left left-0 mt-2';
        break;
    case 'top':
        $alignmentClasses = 'origin-top top-[-3px] right-9';
        break;
    case 'right':
    default:
        $alignmentClasses = 'origin-top-right right-0 mt-2';
        break;
}

switch ($width) {
    case '48':
        $width = 'w-48';
        break;
    case '64':
        $width = 'w-[17rem] lg:w-[64rem]';
        break;
}
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute z-50 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
            style="display: none;">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
