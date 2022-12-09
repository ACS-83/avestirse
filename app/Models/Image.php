<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    // Prevención de vulnerabilidades de campo en asignación masiva
    protected $fillable = [
        'products_id',
        'image'
    ];
}
