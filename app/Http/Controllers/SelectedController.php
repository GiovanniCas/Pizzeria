<?php

namespace App\Http\Controllers;

use App\Models\Header;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SelectedProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SelectedController extends Controller
{
    public function addCart(Request $request){
        
        $quantity = $request->input(['quantity']);
        
        // mi assicuro che la testata dell'ordine esista altrimenti la creo

        if (!session()->has('header_id')) {
            $header = Header::create();
            $id = $header->id;
            session()->put('header_id', $id);
        }

        // qui adesso so quale è la testata dell'ordine/carrello  che devo usare per metterci dentro i prodotti scelti
      
        foreach ($quantity as $id_prodotto => $quantita_desiderata) {
            // se    quantita_desiderata è valida
            
            if($quantita_desiderata != 0){
            
                //controllo esistenza id
                $checker = SelectedProduct::where('product_id', $id_prodotto)->where('header_id' , session()->get('header_id'))->exists();
                
                // allora controllo se il prodotto è già a carrello
                if ($checker) {
                    // se è già a carrello, ne aggiorno la quantità
                    $quantita_desiderata = SelectedProduct::where('product_id' , $id_prodotto)->increment('quantity' , $quantita_desiderata);
                    
                    
                } else  {
                    // altrimenti creo la riga di carrello
                    $priceUni= Product::where('id' , $id_prodotto)->value('price');
                    
                    
                    $prodottoSelezionato = SelectedProduct::create([
                        'product_id' => $id_prodotto,
                        'header_id' => session()->get('header_id'),
                        'quantity' => $quantita_desiderata,
                        'price_uni' => $priceUni,
                        
                    ]);
                    
                    $prodottoSelezionato->save();
                }       
            };
        }    
        
        $prodottiSelezionati = SelectedProduct::all()->where('header_id', session()->get('header_id'));
            
        
        return redirect(route('cart'))->with(compact('prodottiSelezionati'));
    }

    public function updateQuantity(SelectedProduct $prodottoSelezionato){
        
         return view('modificaCarrello', compact('prodottoSelezionato'));
    }
    
    public function modificaQuantita(Request $request , SelectedProduct $prodottoSelezionato){
        $prodottoSelezionato->quantity = $request->quantity;

        $prodottoSelezionato->save();
  
        return redirect(route('cart'));
    }

   
}

