<x-filament::page>
    <h1 class="text-2xl font-bold mb-4">Scan the QR Code</h1>
    @if ($qrCodeUrl)
        <img src="{{ $qrCodeUrl }}" alt="QR Code">
    @else
        <p>Loading QR Code...</p>
    @endif
</x-filament::page>
