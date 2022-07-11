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
use Illuminate\Support\Facades\Log;
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
    
    /**
     *  sezione per aggiunger prodotti al carrello,
     *  per prima cosa se non esiste creo una testate e la metto in sessione;
     *  prendo solo i prodotti che hanno selezionata una quantità maggiore di 0;
     *  se il prodotto esiste già nel carrello, la quantita viene aumentata del valore selezionato
     *  altrimenti il prodotto viene aggiunto normalmente.
     */

    public function addCart(Request $request) {

        $quantity = $request->input(['quantity']);
        
        // mi assicuro che la testata dell'ordine esista altrimenti la creo
        if (!session()->has('header_id')) {
            $header = Header::create(['user_id' => User::GESTORE]);
            $id = $header->id;
            session()->put('header_id', $id);
        }
        
        // qui adesso so quale è la testata dell'ordine/carrello  che devo usare per metterci dentro i prodotti scelti
        foreach ($quantity as $id_prodotto => $quantita_desiderata){
            // se    quantita_desiderata è valida
            
            if($quantita_desiderata != 0) {
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

    // public function updateQuantity(SelectedProduct $prodottoSelezionato){
        
    //      return view('modificaCarrello', compact('prodottoSelezionato'));
    // }
    
    public function modificaQuantita(Request $request ){
        $quantities = $request->input(['quantity']);
        //dd($quantities);
        foreach($quantities as $id_prodottoSelezionato => $nuova_quantita){
            $prodottoSelezionato = SelectedProduct::where('id' , $id_prodottoSelezionato)->where('header_id' , session('header_id'))->update(['quantity' => $nuova_quantita]);
            //dd($prodottoSelezionato->id);
                // if($prodottoSelezionato->quantity = 0){
                //     $prodottoSelezionato->remove();               
                // }
            
        }
        $prodottiSelezionati = SelectedProduct::all()->where('header_id', session('header_id'));//->where('quantity', 0);
        //dd('ciao');
        foreach( $prodottiSelezionati as $prodottoSelezionato) {
            if($prodottoSelezionato->quantity === 0){
                $prodottoSelezionato->delete();
            }
        }

        $prodottiSelezionati = SelectedProduct::all()->where('header_id', session('header_id'));//->where('quantity', 0);

        // dd(count($prodottiSelezionati)) ;
        if(count($prodottiSelezionati) > 0) {
            return view("orderForm");
        } else {
            return redirect(route('welcome'));
            
        }
    }

    public function orderForm(Request $request){
        // $prodottiSelezionati = SelectedProduct::all()->where('header_id', session('header_id'))->where('quantity' , 0);
        // if(count($prodottiSelezionati) != 0) {
        //     return redirect(route("orderForm"))->with(compact('prodottiSelezionati'));
        // } else {
        //     return redirect(route('welcome'));

        // }
        return view("orderForm");
    }

    public function orderSubmit(Request $request ){
        $prodottiSelezionati = SelectedProduct::all()->where('header_id' , session()->get('header_id'));
              
        $totale = 0;
        foreach($prodottiSelezionati as $prodottoSelezionato){
            $tot = $prodottoSelezionato->quantity * $prodottoSelezionato->price_uni;
            $totale += $tot;
            
        }
        $order = Header::where('id' , session('header_id'))->update([
            'name' => $request->input('name'),
            'surname' =>$request->input('surname'),
            'citta' => $request->input('citta'),
            'indirizzo' => $request->input('indirizzo'),
            'cap' => $request->input('cap'),
            'email' => $request->input('email'),
            'data' => $request->input('data'),
            'time' => $request->input('time'),
            'accettazione' => 1,
            'tot' => $totale,
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

    public function categoryView(Category $category){
        $id = $category->id;
        session()->put('category_id' , $id);
        return redirect(route('pizza'));
    }

  
}

