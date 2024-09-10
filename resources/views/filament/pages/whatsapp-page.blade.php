<x-filament::page>
    @if ($whatsapp)
        <img src="data:image/png;base64,{{ $whatsapp['data'] }}" alt="QR Code">
    @else
        <p>Anda Sudah Login...</p>
    @endif
</x-filament::page>
