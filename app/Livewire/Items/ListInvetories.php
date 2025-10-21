<?php

namespace App\Livewire\Items;
use App\Models\Inventory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

use Livewire\Component;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ListInvetories extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Inventory::query())
            ->columns([
                TextColumn::make('item.name')->searchable()
                ->sortable(),
                TextColumn::make('quantity')
                ->sortable()
                ->badge(),
                TextColumn::make('created_at')
                ->toggleable(isToggledHiddenByDefault : true)

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                ->label('Add New')
                ->url(fn (): string => route('inventories.create'))
            ])
            ->recordActions([
                 Action::make('delete')
                ->requiresConfirmation()
                ->color('danger')
                ->action(fn (Inventory $record) => $record->delete())
                ->successNotification(
                     Notification::make()
                        ->title('Deleted successfully')
                        ->success()
                ),
                Action::make('edit')
                    ->url(fn(Inventory $record): string => route('inventory.update', $record))
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.items.list-invetories');
    }
}
