<x-filament::page>
    @if ($whatsapp)
        <img src="data:image/png;base64,{{ $whatsapp['data'] }}" alt="QR Code">
    @else
        <p>Anda Sudah Login...</p>
        <x-filament::button color="danger" class="max-w-xs" wire:click="{{ $logout }}">
            Logout
        </x-filament::button>
    @endif
</x-filament::page>
