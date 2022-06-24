<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable =[
        'product_id',
        'img',
    ];

    public function products(){
        return $this->belongsTo(Product::class );
    }
}
