<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable= [
        'name',
        'description',
        'img',
    ];

    public function products(){
        return $this->belongsTo(Product::class );
    }
}
