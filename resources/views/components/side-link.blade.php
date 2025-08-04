@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-2 py-3 rounded-md transition-all text-white bg-[#F3C623] gap-1'
            : 'flex items-center px-2 py-3 rounded-md transition-all text-gray-500 hover:bg-gray-100 gap-1';
@endphp

<div :class="sidebarToggle ? 'justify-center' : ''">
    <a wire:navigate.hover {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
</a>
</div>