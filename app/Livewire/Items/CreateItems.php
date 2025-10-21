<?php

namespace App\Livewire\Items;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use App\Models\Item;
use Livewire\Component;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;

class CreateItems extends Component implements HasActions, HasSchemas
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
                Section::make('Add the Item')
                    ->description('Fill the form to add a new item.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Item Name')
                            ->required(),

                        TextInput::make('sku')
                            ->required()
                            ->unique(),

                        TextInput::make('price')
                            ->prefix('IDR')
                            ->required()
                            ->numeric(),

                        ToggleButtons::make('status')
                            ->label('Is this item active?')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->grouped(),

                        FileUpload::make('images')
                            ->label('Item Image')
                            ->image()
                            ->directory('items')
                            ->openable()
                            ->downloadable()
                            ->visibility('public')
                            ->maxFiles(1)
                            ->hint('Upload one image for this product.'),
                    ]),
            ])
            ->statePath('data')
            ->model(Item::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Item::create($data);
        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->title('Item Created!')
            ->success()
            ->body("Item '{$record->name}' has been created successfully!")
            ->send();

        // Reset form after creation
        $this->form->fill();
    }

    public function render(): View
    {
        return view('livewire.items.create-items');
    }
}
