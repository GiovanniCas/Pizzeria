<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SelectedProduct;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function welcome(){
        return view("welcome");
    }

    public function pizza(){
      
        $products = Product::all();
    
        return view("pizza" , compact('products'));
    }

    public function cart(){
        $prodottiSelezionati = SelectedProduct::all()->where('header_id' , session()->get('header_id'));
              
        $totale = 0;
        foreach($prodottiSelezionati as $prodottoSelezionato){
            $tot = $prodottoSelezionato->quantity * $prodottoSelezionato->price_uni;
            $totale += $tot;
            
        }
        return view('cart' , compact ('prodottiSelezionati'))->with(compact('totale'));
    }

    
}

