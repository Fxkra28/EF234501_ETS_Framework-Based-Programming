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

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;

class CreateUsers extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                                Section::make('Create user')
                    ->description('Add new user details!')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                        ->required(),
                        TextInput::make('email')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('role')
                            ->required()
                            ->options([
                                'cashier' => 'Cashier',
                                'admin' => 'admin',
                                'other' => 'Other',
                            ])
                            ->native(false),
                        TextInput::make('password')
                            ->required()
                            ->password()
                            ->revealable()
                            ->unique(ignoreRecord: true),
                    ])
            ])
            ->statePath('data')
            ->model(User::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = User::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title('User Created!')
            ->success()
            ->body("User created successfully!")
            ->send();

    }

    public function render(): View
    {
        return view('livewire.management.create-users');
    }
}
