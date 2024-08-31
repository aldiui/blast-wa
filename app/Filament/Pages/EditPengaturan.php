<?php

namespace App\Filament\Pages;

use App\Models\Pengaturan;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class EditPengaturan extends Page implements HasForms
{
    use InteractsWithForms;

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
                            ->prefix('Rp')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('uang_makan')
                            ->label('Uang Makan')
                            ->prefix('Rp')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('field_trip')
                            ->label('Field Trip')
                            ->prefix('Rp')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('daftar_ulang')
                            ->label('Daftar Ulang')
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
