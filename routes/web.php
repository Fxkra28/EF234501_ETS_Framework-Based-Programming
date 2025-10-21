<?php

use App\Livewire\Customer\CreateCustomers;
use App\Livewire\Customer\EditCustomers;
use App\Livewire\Customer\ListCustomers;
use App\Livewire\Items\CreateInventory;
use App\Livewire\Items\CreateItems;
use App\Livewire\Items\EditInventory;
use App\Livewire\Items\EditItems;
use App\Livewire\Items\ListInvetories;
use App\Livewire\Items\ListItems;
use App\Livewire\Management\CreatePaymentMethods;
use App\Livewire\Management\CreateUsers;
use App\Livewire\Management\EditPaymentMethods;
use App\Livewire\Management\EditUsers;
use App\Livewire\Management\ListPaymentMethods;
use App\Livewire\Management\ListUsers;
use App\Livewire\Sales\ListSales;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::get('/sales/{sale}/receipt', function (\App\Models\Sale $sale) {
    $sale->load(['saleItems.item', 'customer', 'paymentMethod']);
    return view('pdf', ['records' => collect([$sale])]);
})->name('sales.receipt');

Route::middleware(['auth'])->group(function () {
    //users
    Route::get('/manage-users',ListUsers::class)->name('users.index');
    Route::get('/edit-user/{record}',EditUsers::class)->name('user.update');
    Route::get('/create-user',CreateUsers::class)->name('users.create');
    //items
    Route::get('/manage-items',ListItems::class)->name('items.index');
    Route::get('/edit-items{record}',EditItems::class)->name('items.update');
    Route::get('/create-item',CreateItems::class)->name('items.create');
    //inventort
    Route::get('/manage-inventories',ListInvetories::class)->name('inventories.index');
    Route::get('/edit-inventory/{record}',EditInventory::class)->name('inventory.update');
    Route::get('/create-inventory',CreateInventory::class)->name('inventories.create');
    //sales
    Route::get('/manage-sales',ListSales::class)->name('sales.index');
    //customers
    Route::get('/manage-customers',ListCustomers::class)->name('customers.index');
    Route::get('/edit-customer/{record}',EditCustomers::class)->name('customer.update');
    Route::get('/create-customer',CreateCustomers::class)->name('customers.create');
    //payment method
    Route::get('/manage-payment-methods',ListPaymentMethods::class)->name('payment.method.index');
    Route::get('/edit-payment-method/{record}',EditPaymentMethods::class)->name('payment-method.update');
    Route::get('/create-payment-method',CreatePaymentMethods::class)->name('payment-method.create');

}); 

require __DIR__.'/auth.php';
