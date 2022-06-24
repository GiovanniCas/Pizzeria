<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Image;
use App\Models\Header;
use App\Mail\AdminMail;
use App\Models\Product;
use App\Models\Category;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\SelectedProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class PublicController extends Controller
{
    public function welcome(){
        $categories = Category::all();
        return view("welcome" , compact('categories'));
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

    public function orderForm(){
        return view("orderForm");
    }

    public function orderSubmit(Request $request ){
        
        $order = Header::where('id' , session()->get('header_id'))->update([
        'name' => $request->input('name'),
        'surname' =>$request->input('surname'),
        'citta' => $request->input('citta'),
        'indirizzo' => $request->input('indirizzo'),
        'cap' => $request->input('cap'),
        'email' => $request->input('email'),
        'data' => $request->input('data'),
        'time' => $request->input('time'),
        'accettazione' => 1,
        ]);  
        

        $name = $request->input('name');
        $email = $request->input('email');
        $contact = compact("name" , "email" );
        
        //invio mail
        Mail::to($email)->send(new ContactMail($contact));
        Mail::to("revisore@pizzeria.it")->send(new AdminMail($contact));
        
        session()->forget('header_id');

        return redirect(route("welcome"))->with("message" , "Grazie per averci scelto, il suo ordine è stato preso in carico!");

    }

  
}

