<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SelectedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        
    ];


    public function products(){
        return $this->hasMany(Product::class);
    }

    public function headers(){
        return $this->belongsTo(Header::class);
    }
}
