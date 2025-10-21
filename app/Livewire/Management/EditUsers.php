<?php

namespace App\Livewire\Management;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Models\User;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class EditUsers extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public User $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                                Section::make('Edit user')
                    ->description('update the user details as you wish!!')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name'),
                        TextInput::make('email')
                            ->unique(ignoreRecord: true),
                        Select::make('role')
                            ->options([
                                'Kadep EDD'=> 'Kadep EDD',
                                'Sekdep EDD' => 'Sekdep EDD',
                                'Kabiro BE'=> 'Kabiro BE',
                                'Wakabiro BE' => 'Wakabiro BE',
                                'Staff BE' => 'Staff BE',
                            ])
                            ->native(false),
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->unique(ignoreRecord: true),
                    ])
                ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);
        Notification::make()
        ->title('User Updated!')
        ->success()
        ->body("{$this->record->name} has been updated successfully!")
        ->send();

    }

    public function render(): View
    {
        return view('livewire.management.edit-users');
    }
}
