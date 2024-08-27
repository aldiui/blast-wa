<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Pengaturan;
use Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;

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
        if (auth()->user()->role_id == 1) {
            return $form
                ->schema([
                    Forms\Components\Section::make('Pengaturan')
                        ->schema([
                            Forms\Components\FileUpload::make('logo')
                                ->directory('pengaturan')
                                ->visibility('private')
                                ->imageEditor(),
                            Forms\Components\Placeholder::make('Logo'),
                                // ->helperText(new HtmlString("<img src='" . getPengaturan()->logo . "' width='100px'>")),
                            Forms\Components\TextInput::make('nama')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('nomor_handphone')
                                ->label('Nomor Handphone')
                                ->tel()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Textarea::make('alamat')
                                ->required(),
                        ])->columns(2),
                ])->statePath('data');
        } else {
            return $form
                ->schema([
                    Forms\Components\Section::make('Pengaturan')
                        ->schema([
                            Forms\Components\Placeholder::make('Logo')
                                ->helperText(new HtmlString("<img src='" . getPengaturan()->logo . "' width='100px'>")),
                            Forms\Components\Placeholder::make('nama')
                                ->content(fn(): ?string => getPengaturan()->nama),
                            Placeholder::make('email')
                                ->content(fn(): ?string => getPengaturan()->email),
                            Forms\Components\Placeholder::make('nomor_handphone')
                                ->label('Nomor Handphone')
                                ->content(fn(): ?string => getPengaturan()->nomor_handphone),
                            Forms\Components\Placeholder::make('alamat')
                                ->content(fn(): ?string => getPengaturan()->alamat),
                        ])->columns(2),
                ])->statePath('data');
        }
    }

    public function getFormActions(): array
    {
        if (auth()->user()->role_id == 1) {
            return [
                Action::make('save')
                    ->label('Simpan')
                    ->submit('save'),
            ];
        } else {
            return [];
        }
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

        Notification::make()
            ->success()
            ->title('Pengaturan Berhasil diubah')
            ->send();
    }

}