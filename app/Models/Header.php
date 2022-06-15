<?php

namespace App\Models;

use App\Models\SelectedProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Header extends Model
{
    use HasFactory;

    protected $fillable= [
        'name',
        'surname',
        'citta',
        'indirizzo',
        'cap',
        'email',
        'data',
        'time',
        'accettazione',
    ];

    public function selectedProducts(){
        return $this->hasMany(SelectedProduct::class);
    }
}
