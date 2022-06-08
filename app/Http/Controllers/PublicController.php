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
      
        $products = DB::table('products')->where('category_id', '=', 1)->get();
        return view("pizza" , compact('products'));
    }

    public function cart(){
        $idsPizze = SelectedProduct::all();
        return view('cart' , compact ('idsPizze'));
    }

    
}

