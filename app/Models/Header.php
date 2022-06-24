<?php

namespace App\Models;

use App\Models\User;
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

    const PREPARAZIONE = 1;
    const IN_CONSEGNA = 2;
    const CONSEGNATO = 3;

    public function selectedProducts(){
        return $this->hasMany(SelectedProduct::class);
    }

    
    public function users(){
        return $this->hasOne(User::class);
    }
}
