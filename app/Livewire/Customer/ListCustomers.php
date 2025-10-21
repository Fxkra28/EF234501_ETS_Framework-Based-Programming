<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
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

class ListCustomers extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Customer::query())
            ->columns([
                TextColumn::make('name')
                ->searchable()
                ->sortable(),
                TextColumn::make('email')
                ->sortable()
                ->searchable(),
                TextColumn::make('phone')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                ->label('Add New Customer')
                ->url(fn() => route('customers.create'))
            ])
            ->recordActions([
                    Action::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn(Customer $record) => $record->delete())
                    ->successNotification(
                        Notification::make()
                            ->title('Customer Deleted successfully')
                            ->success()
                    ),
                    Action::make('edit')
                    ->url(fn(Customer $record): string => route('customer.update', $record))
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.customer.list-customers');
    }
}
