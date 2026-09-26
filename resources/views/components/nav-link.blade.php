@props(['href' => null, 'active' => false, 'disabled' => false])

@if ($disabled)
    <span {{ $attributes->merge(['class' => 'flex items-center gap-3 px-3 py-2 text-sm rounded-md text-gray-400 cursor-not-allowed']) }}>
        {{ $slot }}
    </span>
@else
    <a href="{{ $href }}"
        {{ $attributes->merge(['class' => 'flex items-center gap-3 px-3 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-100 transition-colors ' . ($active ? 'sidebar-link-active' : '')]) }}>
        {{ $slot }}
    </a>
@endif