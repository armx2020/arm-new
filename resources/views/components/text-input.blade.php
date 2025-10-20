@props(['disabled' => false, 'error' => false])

@php
    $border = $error ? 'border-red-300' : 'border-gray-300';
@endphp

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => $border . ' focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm',
]) !!}>
