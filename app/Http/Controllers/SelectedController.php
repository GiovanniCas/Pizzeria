<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SelectedProduct;

class SelectedController extends Controller
{
    public function addCart(Request $request){
        
        //dd($request->all());
     
        
        $quantity = $request->input(['quantity']);
        //dd($quantity);
        $arrayProdottiSelezionati = [];
        // foreach($quantity as $qty){
            
        //     if($qty > 0){
        //         array_push($arrayProdottiSelezionati , $qty);
        //     }
        // } 
        // dd( $arrayProdottiSelezionati);

        $idsPizze = array_keys($quantity);
        //dd($idsPizze);
        return view('cart' , compact('idsPizze'));
    }
}
