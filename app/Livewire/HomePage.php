<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use Filament\Forms\Components\Livewire\Attribute\Title;
use Livewire\Attributes\Title as AttributesTitle;
use Livewire\Component;

#[AttributesTitle('Home Page - StyleStream')]
class HomePage extends Component
{
    public function render()
    {
        $brands = Brand::where('is_active',1)->get();
        $categories = Category::where('is_active',1)->get();

        return view('livewire.home-page',[
            'brands'=>$brands,
            'categories'=> $categories,
        ]);
    }
}
