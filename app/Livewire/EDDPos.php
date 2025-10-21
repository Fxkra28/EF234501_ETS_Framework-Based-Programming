<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleItem;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Inventory;
use Filament\Actions\Action;
use App\Models\PaymentMethod;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Filament\Notifications\Notification;

class EDDPos extends Component
{

    //properties
    public $items;
    public $customers;
    public $paymentMethods;
    public $search = '';
    public $cart = [];

    //properties for checkout 
    public $customer_id = null;
    public $payment_method_id = null;
    public $paid_amount = 0;


    public function mount()
    {
        //load all the items, 
        // $this->items = Item::with([
        //     'inventory' => function ($builder) {
        //         $builder->where('quantity', '>', 0);
        //     }
        // ])
        //     ->where('status', 'active')
        //     ->get();

        $this->items = Item::whereHas('inventory', function ($builder) {
            $builder->where('quantity', '>', 0);
        })
            ->with('inventory')
            ->where('status', 'active')
            ->get();
        //load customers
        $this->customers = Customer::all();

        //load payment methods
        $this->paymentMethods = PaymentMethod::all();

    }
    #[Computed]
    public function filteredItems()
    {
        if (empty($this->search)) {
            return $this->items;
        }

        return $this->items->filter(function ($item) {
            return str_contains(strtolower($item->name), strtolower($this->search))
                || str_contains(strtolower($item->sku), strtolower($this->search));
        });

    }

    #[Computed]
    public function subtotal()
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    #[Computed]
    public function tax()
    {
        // Tax is 11% of subtotal
        return round($this->subtotal * 0.11, 2);
    }

    #[Computed]
    public function total()
    {
        // Total = subtotal + tax
        return round($this->subtotal + $this->tax, 2);
    }

    #[Computed]
    public function change()
    {
        // Change is paid amount minus total, but not negative
        return max(0, round($this->paid_amount - $this->total, 2));
    }


    public function addToCart($itemId)
    {

        //access the item from db get its inventory
        $item = Item::find($itemId);

        //inventory
        $inventory = Inventory::where('item_id', $itemId)->first();
        if (!$inventory || $inventory->quantity <= 0) {
            Notification::make()
                ->title('This item is out of stock!')
                ->danger()
                ->send();
            return;
        }
        //
        if (isset($this->cart[$itemId])) {
            $currentQuantity = $this->cart[$itemId]['quantity'];
            if ($currentQuantity >= $inventory->quantity) {
                Notification::make()
                    ->title("Cannot add more. Only {$inventory->quantity} in stock")
                    ->danger()
                    ->send();
                return;
            }
            //add one items
            $this->cart[$itemId]['quantity']++;
        }else{
            $this->cart[$itemId] = [
                'id' => $item->id,
                'name' => $item->name,
                'sku' => $item->sku,
                'price' => $item->price,
                'quantity' => 1,
                'images' => $item->images[0] ?? null, // add this line
            ];
        }
    }

    //remove items from the cart
    public function removeFromCart($itemId){
        unset($this->cart[$itemId]);
    }

    //update the quantity on the cart for that item
    public function updateQuantity($itemId,$quantity){
        //ensure qunatoty of an item is not less than 1
        $quantity = max(1,(int) $quantity);

        //get inventory
        $inventory = Inventory::where('item_id',$itemId)->first();

        if ($quantity > $inventory->quantity) {
            Notification::make()
                    ->title("Cannot add more. Only {$inventory->quantity} in stock")
                    ->danger()
                    ->send();
            $this->cart[$itemId]['quantity'] = $inventory->quantity;
        }else{
            $this->cart[$itemId]['quantity'] = $quantity;
        }
    }

    //checkout
    public function checkout(){
        //check if the cart is not empty 
        if (empty($this->cart)) {
            Notification::make()
            ->title('Failed Sale!')
            ->body('Your cart is empty!')
            ->danger()
            ->send();
            return;
        }

        //basic validation for paid amount
        if ($this->paid_amount < $this->total) {
            Notification::make()
            ->title('Failed Sale!')
            ->body('Paid Amount is less than total!')
            ->danger()
            ->send();
            return;
        }

        //create the sale... db transaction
        try {
            //code...
        
        DB::beginTransaction();

        //create a sale
        $sale = Sale::create([
            'total' => $this->total,
            'paid_amount' => $this->paid_amount,
            'customer_id' => $this->customer_id,
            'payment_method_id' => $this->payment_method_id,
        ]);

        // create the sale items

        foreach($this->cart as $item){
            SaleItem::create([
                'sale_id' => $sale->id,
                'item_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            //update the stock
            $inventory = Inventory::where('item_id',$item['id'])->first();
            if ($inventory) {
                $inventory->quantity -= $item['quantity'];
                $inventory->save();
            }
        }

        DB::commit();

        //reset cart
        $this->cart = [];

        //reset other properties
        $this->search = '';
        $this->customer_id = null;
        $this->payment_method_id = null;
        $this->paid_amount = 0;

        Notification::make()
                ->title('Sale Completed')
                ->body('Do you want to print the receipt?')
                ->success()
                ->duration(10000)
                ->actions([
                    Action::make('print')
                        ->button()
                        ->label('Yes, Print Receipt')
                        ->url(route('sales.receipt', ['sale' => $sale->id]), shouldOpenInNewTab: true)
                        ->color('primary')
                        // ->url(route('sales.receipt', $sale))
                        ->openUrlInNewTab(false)
                        ->extraAttributes([
                            'onclick' => 'event.preventDefault(); printReceipt(this.href);'
                        ]),
                    // NotificationAction::make('cancel')
                    //     ->button()
                    //     ->label('No')
                    //     ->color('secondary'),
                ])
                ->send();


        } catch (\Exception $th) {
            DB::rollBack();

            logger()->error('Checkout error: ' . $th->getMessage());
            Notification::make()
                ->title('Failed Sale!')
                ->body('Error: ' . $th->getMessage())
                ->danger()
                ->send();

        }
    }

    public function render()
    {
        return view('livewire.e-d-d-pos');
    }
}

