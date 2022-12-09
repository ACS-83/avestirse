<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    // Prevención de asignación masiva
    protected $fillable = [
    'name',
    'description',
    'price',
    'stock',
    ];
    // Método para indicar que "un producto tiene muchas imágenes"
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
