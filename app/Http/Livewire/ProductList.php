<?php

namespace App\Http\Livewire;


use App\Models\Image;
use Livewire\Component;
use App\Models\Products;
use Illuminate\Support\Facades\DB;

class ProductList extends Component
{
    // Variables para usar con Livewire
    // Paginación
    public $page = 6;
    // Columna a ordenar
    public $sortColumnName = 'updated_at';
    // Orden descendente
    public $sortDire = 'desc';
    
    // Método load para sumar 6 con cada llamada
    public function load()
    {
        $this->page += 6;
    }

    // Método render de Livewire
    public function render()
    {
        // Prepara variable con sentencia eloquent que extrae productos por columna, los ordena y dispara
        // un método PAGINATE con la paginación que exista en ese momento
        $products = Products::orderBy($this->sortColumnName, $this->sortDire)->
        paginate($this->page);
        // Extrae las imágenes de producto mediante sentencia eloquent
        $images = Products::join('images', 'images.products_id', '=', 'products.id')->get()->unique('products_id');
        // De la variable anterior se convierte a ARRAY por la columa IMAGE
        $images = $images->pluck('image', 'products_id');
        // Retorna un componente LIVEWIRE a la vista con las variables de producto e imágenes en un array
        return view('livewire.product-list', [
            'products' => $products,
            'images' => $images
        ]);
    }
}
