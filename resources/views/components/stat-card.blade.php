@props(['label', 'value'])

<div class="bg-white rounded-lg shadow-sm p-5">
    <p class="text-sm text-gray-500">{{ $label }}</p>
    <p class="text-2xl font-semibold text-gray-800 mt-1">
        @if (is_null($value))
            <span class="text-gray-300 text-base italic">Belum tersedia</span>
        @else
            {{ $value }}
        @endif
    </p>
</div>