<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Pengaturan;
use Illuminate\Support\HtmlString;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class EditPengaturan extends Page implements HasForms
{
    use InteractsWithForms, HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.edit-pengaturan';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $modelLabel = 'Pengaturan';

    protected static ?string $slug = 'pengaturan';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
        try {
            $pengaturan = Pengaturan::find(1);

            if ($pengaturan) {
                $this->form->fill($pengaturan->attributesToArray());
            }

        } catch (Halt $exception) {
            return;
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->directory('pengaturan')
                            ->visibility('private')
                            ->imageEditor(),
                        Forms\Components\Placeholder::make('Logo')
                            ->helperText(new HtmlString("<img alt='Logo' src='" . getPengaturan()->logo . "' width='100px'>")),
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Sekolah')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('no_telepon')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('alamat')
                            ->required(),
                        Forms\Components\TextInput::make('syahriyah')
                            ->label('Syahriyah')
                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                            ->prefix('Rp')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('uang_makan')
                            ->label('Uang Makan')
                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                            ->prefix('Rp')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('field_trip')
                            ->label('Field Trip')
                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                            ->prefix('Rp')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('daftar_ulang')
                            ->label('Daftar Ulang')
                            ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                            ->prefix('Rp')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),
            ])->statePath('data');
    }

    public function getFormActions(): array
    {

        return [
            Action::make('save')
                ->label('Simpan')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $pengaturan = Pengaturan::find(1);
            if (!is_null($data['logo'])) {
                if (basename($pengaturan->logo) != 'logo.jpeg') {
                    Storage::delete('public/pengaturan/' . basename($pengaturan->logo));
                }
            } else {
                $data['logo'] = 'pengaturan/' . basename($pengaturan->logo);
            }

            $pengaturan->update($data);

        } catch (Halt $exception) {
            return;
        }

        redirect()->to('/pengaturan');
        Notification::make()
            ->success()
            ->title('Pengaturan Berhasil diubah')
            ->send();
    }

}
