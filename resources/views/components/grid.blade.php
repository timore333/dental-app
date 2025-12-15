{{-- Usage: <x-grid :cols="2" gap="4"> ... </x-grid> --}}

<div class="grid grid-cols-{{ $cols }} gap-{{ $gap }} md:grid-cols-{{ min($cols + 1, 12) }} lg:grid-cols-{{ $cols }}">
    {{ $slot }}
</div>
