{{-- resources/views/filament/pages/pengaturan-page.blade.php --}}
<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}
        <x-filament::button type="submit">
            Simpan Pengaturan
        </x-filament::button>
    </form>
</x-filament::page>
