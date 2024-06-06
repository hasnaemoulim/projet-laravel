<?php

namespace App\Livewire;

use App\Models\Category;
use Filament\Forms\Components\Livewire\Attribute\Title;
use Livewire\Attributes\Title as AttributesTitle;

use Livewire\Component;

#[AttributesTitle('Categories Page - StyleStream')]

class CategoriesPage extends Component
{
    public function render()
    {
        $categories=Category::where('is_active',1)->get();
        return view('livewire.categories-page',[
            'categories'=>$categories,
        ]
    );
    }
}
