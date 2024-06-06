<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

use Laravel\Prompts\Prompt;
use Livewire\WithPagination;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title as AttributesTitle;
use Filament\Forms\Components\Livewire\Attribute\Title;

#[AttributesTitle('Product Detail - StyleStream')]
class ProductDetailPage extends Component
{
    use LivewireAlert;
    public $slug;
    public $quantity=1;
    public function mount($slug){
$this->slug=$slug;
    }

    public function increaseQty(){
        $this->quantity++;
    }
    public function decreaseQty(){
        if($this->quantity >1){
            $this->quantity--;

        }
    }
    //add product to cart methode
    public function addToCart($product_id){
        $total_count =CartManagement::addItemsToCartWithQty($product_id,$this->quantity);
        $this->dispatch('update-cart-count',total_count:$total_count)->to(Navbar::class);
        $this->alert('success', 'Product added to the cart successfully!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
           ]);


        }

    public function render()
    {
        return view('livewire.product-detail-page',  [
            'product'=>Product::where('slug',$this->slug)->firstOrFail(),
        ]
    );
    }
}
