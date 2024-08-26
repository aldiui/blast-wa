<?php
namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Pengaturan;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class PengaturanPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.pengaturan-page';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $modelLabel = 'Pengaturan';

    protected static ?string $slug = 'pengaturan';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Pengaturan';

    // Tambahkan method untuk mendefinisikan form
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Sekolah')
                    ->required(),

            ]);
    }

    // Method untuk handling form submission
    public function submit()
    {
        $this->validate('');

        $pengaturan = Pengaturan::create($this->form->getState());

        session()->flash('message', 'Pengaturan berhasil disimpan.');

        return redirect()->route('filament.pages.pengaturan-page');
    }
}
