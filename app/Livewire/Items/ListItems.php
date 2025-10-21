<?php

namespace App\Livewire\Items;
use App\Models\Item;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

use Livewire\Component;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Notifications\Notification;



class ListItems extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Item::query())
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('sku')->searchable()
                ->sortable(),
                TextColumn::make('price')->money('IDR')->sortable(),
                TextColumn::make('status')->badge()->searchable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                ->label('Add New')
                ->url(fn (): string => route('items.create'))
            ])

            ->recordActions([
                Action::make('delete')
                ->requiresConfirmation()
                ->color('danger')
                ->action(fn (Item $record) => $record->delete())
                ->successNotification(
                     Notification::make()
                        ->title('Deleted successfully')
                        ->success()
                ),
                Action::make('edit')
                ->url(fn (Item $record): string => route('items.update', $record)) 
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.items.list-items');
    }
}
