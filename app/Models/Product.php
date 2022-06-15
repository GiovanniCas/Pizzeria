<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Category;
use App\Models\SelectedProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
    ];

    public function categories(){
        return $this->belongsToMany(Category::class , 'category_id');
    }

    public function selectedProducts(){
        return $this->hasMany(SelectedProduct::class);
    }
}
