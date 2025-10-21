<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditItems extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Item $record;
    public ?array $data = [];

    /** Populate form from database */
    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    /** Define the schema (form structure) */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit the Item')
                    ->description('Update the item details as you wish!')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Item Name')
                            ->required(),

                        TextInput::make('sku')
                            ->unique(ignoreRecord: true),

                        TextInput::make('price')
                            ->prefix('IDR')
                            ->numeric(),

                        ToggleButtons::make('status')
                            ->label('Is this item active?')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->grouped(),

                        FileUpload::make('images')
                            ->label('Item Images')
                            ->image()
                            ->multiple()
                            ->directory('items') // stored in storage/app/public/items
                            ->reorderable()
                            ->openable()
                            ->downloadable()
                            ->visibility('public')
                            ->maxFiles(1),
                    ]),
            ])
            ->statePath('data')
            ->model($this->record);
    }

    /** Save form changes */
    public function save(): void
    {
        $data = $this->form->getState();
        $this->record->update($data);

        Notification::make()
            ->title('Item Updated!')
            ->success()
            ->body("Item {$this->record->name} has been updated successfully!")
            ->send();
    }

    public function render(): View
    {
        return view('livewire.items.edit-items');
    }
}
