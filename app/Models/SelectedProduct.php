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
        'header_id',
        'quantity',
        'price_uni' ,
        
    ];


    public function products(){
        return $this->belongsTo(Product::class , 'product_id');
    }

    public function headers(){
        return $this->belongsTo(Header::class , 'header_id');
    }
}
