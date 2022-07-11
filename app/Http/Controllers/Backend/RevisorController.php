<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Header;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SelectedProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;


class RevisorController extends Controller
{
    public function staff() {
        if (Gate::denies('Gestore')) {
            abort(403);
        }      
        return view('staff');
    }

    public function addStaff(Request $request) {
        if (Gate::denies('Gestore')) {
            abort(403);
        }  
        $user = new User();
        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->ruolo = $request->input('ruolo');

        $user->save();

        return redirect(route('staff'));
    }

 

    public function updateOrder(Header $header){
        if (Gate::denies('Cuoco')) {
            abort(403);
        } 
        $header->accettazione = 2;
        $header->save();
        return redirect(route('orderList'));
    }

    public function destroyOrder(Header $header) {
        if (Gate::denies('Cuoco')) {
            abort(403);
        }
        $header->delete();
    
        return redirect(route('orderList'));
    }

    //fattorino che accetta
    public function acceptOrder(Header $header ) {
        if (Gate::denies('Fattorino')) {
            abort(403);
        }
        $header->user_id = Auth::user()->id;
        $header->save();
        return redirect(route('orderList'))->with(compact('header'));
    }

    public function deliveredOrder(Header $header){
        if (Gate::denies('Fattorino')) {
            abort(403);
        }
        $header->accettazione = 3;
        $header->save();
        return redirect(route('orderList'));  //prima c'era fattorino
    }

    public function utenti(){
        if (Gate::denies('Gestore')) {
            abort(403);
        }

        if(empty(session('searchUser')) && empty(session('ruolo'))) {
            $users = User::paginate(4);
        }else{
            $search = session('searchUser');
            $ruolo = session('ruolo');
            $q = User::query();

            if($search){
                $q = $q->where('name','LIKE','%'.$search.'%')->paginate(4);
            }
    
            if($ruolo){
                foreach($ruolo as $m) {

                    if($m === "Tutte"){
                        $q = $q->where('ruolo' , '<>' , 'Tutte');
                    } else{
                        $q = $q->where('name','LIKE','%'.$search.'%')
                            ->where('ruolo', $m)
                            ->orWhere('ruolo', $m);
                    }
                } 
            }
            
            $q = $q->paginate(4);
            $users = $q;
            //dd($users);
        }
      
        session()->forget('users');

        return view('utenti', compact('users'));
    }

    public function updateUtente(User $user){
        if (Gate::denies('Gestore')) {
            abort(403);
        } 
        return view('modificaUtente' , compact('user'));
    }

    public function updateUser(User $user , Request $request){
        if (Gate::denies('Gestore')) {
            abort(403);
        } 

        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->ruolo = $request->ruolo;
        $user->save();
        
        return redirect(route('utenti'));

    }

    public function deleteUser(User $user ){
        if (Gate::denies('Gestore')) {
            abort(403);
        } 
    

        $user->delete();
        return redirect(route('utenti'));
    }

    public function searchUser(Request $request){
        
        $search = $request->search;
        $ruolo = $request->ruolo;

        session()->put('searchUser' , $search);
        session()->put('ruolo' , $ruolo);

        return redirect(route('utenti'));
    }

    public function orderList(){
     
        $prodottiSelezionati = SelectedProduct::all();
        if(empty(session('searchOrder')) && empty(session('accettazione'))){
            $orders = Header::where('user_id' , User::GESTORE)
                    ->orWhere('user_id' , Auth::user()->id) 
                    ->where('accettazione' , Header::IN_CONSEGNA) 
                    ->paginate(4);
            
        }else{
            $search = session('searchOrder');
            $accettazione = session('accettazione');
            $q = Header::query();

            if($search){
                $q = $q->where('name','LIKE','%'.$search.'%');
            }

            if($accettazione){
                foreach($accettazione as $m){
                    if($m === "Tutte"){
                        $q = $q->where('accettazione' , '<>' , 'Tutte');
                    } else{
                    $q = $q->where('name','LIKE','%'.$search.'%')
                        ->where('accettazione', $m)
                        ->orWhere('accettazione', $m);
                    }
                }  
            }
            $q = $q->paginate(4);
            $orders = $q;
        }

        return view('listaOrdini' , compact('orders'))->with(compact('prodottiSelezionati'));
    }

    public function searchOrder(Request $request){
       
        $search = $request->search;
        $accettazione = $request->accettazione;

        session()->put('searchOrder' , $search);
        session()->put('accettazione' , $accettazione);

        return redirect(route('orderList'));
    }

    public function report(){
        
        // $pippo= Header::groupBy('data')
        // // ->where('data' ,date('Y-m-d',strtotime("-6 days")))
        // // ->orWhere('data' ,date('Y-m-d',strtotime("-5 days")))
        // // ->orWhere('data' ,date('Y-m-d',strtotime("-4 days")))
        // // ->orWhere('data' ,date('Y-m-d',strtotime("-3 days")))
        // // ->orWhere('data' ,date('Y-m-d',strtotime("-2 days")))
        // // ->orWhere('data' ,date('Y-m-d',strtotime("-1 days")))
        // // ->orWhere('data' ,date('Y-m-d'))
        // ->sum('tot');

        $totale = [ 
            $giorno_uno= Header::groupBy('data')->where('data' ,date('Y-m-d',strtotime("-6 days")))->sum('tot'),
            $giorno_due= Header::groupBy('data')->where('data' ,date('Y-m-d',strtotime("-5 days")))->sum('tot'),
            $giorno_tre= Header::groupBy('data')->where('data' ,date('Y-m-d',strtotime("-4 days")))->sum('tot'),
            $giorno_quattro= Header::groupBy('data')->where('data' ,date('Y-m-d',strtotime("-3 days")))->sum('tot'),
            $giorno_cinque= Header::groupBy('data')->where('data' ,date('Y-m-d',strtotime("-2 days")))->sum('tot'),
            $giorno_sei= Header::groupBy('data')->where('data' ,date('Y-m-d',strtotime("-1 days")))->sum('tot'),
            $giorno_sette= Header::groupBy('data')->where('data' ,date('Y-m-d'))->sum('tot'),
        ];
        
        $date= [
            date('Y-m-d',strtotime("-6 days")) ,
            date('Y-m-d',strtotime("-5 days")) ,
            date('Y-m-d',strtotime("-4 days")) ,
            date('Y-m-d',strtotime("-3 days")) ,
            date('Y-m-d',strtotime("-2 days")) , 
            date('Y-m-d',strtotime("-1 days")) ,
            date('Y-m-d') , 
        ];
        
       
        return view('report' , compact('date'))->with(compact('totale'));
    }

        
        //dd($pippo);
        // $ordini = Header::where('data' , '<>' , null)->get();
        // //dd($ordini);
        // $totali_per_giorno = [];
       
        // foreach($ordini as $ordine){
        //     $ordini = $ordini->groupBy('data')->SUM('tot');
        //     dd($ordini);
        //     $totale_giornaliero = [$ordine->data => $ordine->tot];
        //     $controllo_esistenza_data = $ordini->where('data' , $ordine->data)->exists();
        //     if(!$controllo_esistenza_data){
        //         //dd('pio');
        //         array_push($totali_per_giorno , $totale_giornaliero);
        //         // $q = $totali_per_giorno->groupBy('data')->get();
        //         // dd($q);
                                
        //     } else {
        //         dd('ciao');
        //         $pippo = $ordine->where('data' , $ordine->data)->increment('tot' , $ordine->tot) ;
        //     }        
        //         //array_push($totali_per_giorno , $totale_giornaliero);
        //         //dd($totale);
        // }
        // // foreach($totale_giornaliero as $ordine->data => $ordine->tot){
        // //     //dd($totali_per_giorno);
        // //     $controllo_esistenza_data = array_search($ordine->data , $totali_per_giorno);
        // //     //$ordine->where('data' , $ordine->data)->exists();
        // //     //array_search( $ordine->data , $totali_per_giorno);
        // //     //dd( $controllo_esistenza_data);
        // //     if(!$controllo_esistenza_data){
        // //         //dd('pio');
        // //         array_push($totali_per_giorno , $totale_giornaliero);
                                
        // //     } else {
        // //         dd('ciao');
        // //         $pippo = $ordine->where('data' , $ordine->data)->increment('tot' , $ordine->tot) ;
        // //     }        
            
        // //}
            
        
        // dd($totali_per_giorno);
        
               
        // $prezzo_totale_ordini = [];
        // $prodottiSelezionati = SelectedProduct::all();
        // foreach($prodottiSelezionati as $prodottoSelezionato){
        //     $prezzo = $prodottoSelezionato->quantity * $prodottoSelezionato->price_uni;
        //     $tot = [$prodottoSelezionato->id => $prezzo ];
            
        //     array_push($prezzo_totale_ordini , $tot);
        // }

        
        // // foreach($prezzo_totale_ordini as $id_prodotto_ordine => $prezzo_totale){
        // //     $ordini =  Header::where('id' , $id_prodotto_ordine)->orWhere('data', '<>', null )->groupBy('data');
            
        // //    // dd($ordini);
        // // }
        
                    

        // $ordini1 = Header::where('data' ,date('Y-m-d',strtotime("-6 days")))->get();
        // $totale1= 0;
        // foreach( $ordini1 as $ordine){
        //     $prodottiSelezionati = SelectedProduct::all()->where('header_id' , $ordine->id);
        //     foreach($prodottiSelezionati as $prodottoSelezionato){
        //         $tot = $prodottoSelezionato->quantity * $prodottoSelezionato->price_uni;
        //         $totale1 += $tot;
        //     }
        // }
        // array_push($totale , $totale1 );

        // $ordini2 = Header::where('data' ,date('Y-m-d',strtotime("-5 days")))->get();
        // $totale2 = 0;
        // foreach( $ordini2 as $ordine){
        //     $prodottiSelezionati = SelectedProduct::all()->where('header_id' , $ordine->id);
        //     foreach($prodottiSelezionati as $prodottoSelezionato){
        //         $tot = $prodottoSelezionato->quantity * $prodottoSelezionato->price_uni;
        //         $totale2 += $tot;
                
        //     }
        // }
        // array_push($totale , $totale2);

        // $ordini3 = Header::where('data' ,date('Y-m-d',strtotime("-4 days")))->get();
        // $totale3 = 0;
        // foreach( $ordini3 as $ordine){
        //     $prodottiSelezionati = SelectedProduct::all()->where('header_id' , $ordine->id);
        //     foreach($prodottiSelezionati as $prodottoSelezionato){
        //         $tot = $prodottoSelezionato->quantity * $prodottoSelezionato->price_uni;
        //         $totale3 += $tot;
                
        //     }
        // }
        // array_push($totale , $totale3);

        // $ordini4 = Header::where('data' ,date('Y-m-d',strtotime("-3 days")))->get();
        // $totale4 = 0;
        // foreach( $ordini4 as $ordine){
        //     $prodottiSelezionati = SelectedProduct::all()->where('header_id' , $ordine->id);
        //     foreach($prodottiSelezionati as $prodottoSelezionato){
        //         $tot = $prodottoSelezionato->quantity * $prodottoSelezionato->price_uni;
        //         $totale4 += $tot;
                
                
        //     }
        // }
        // array_push($totale , $totale4);

        // $ordini5 = Header::where('data' ,date('Y-m-d',strtotime("-2 days")))->get();
        // $totale5 = 0;
        // foreach( $ordini5 as $ordine){
        //     $prodottiSelezionati = SelectedProduct::all()->where('header_id' , $ordine->id);
        //     foreach($prodottiSelezionati as $prodottoSelezionato){
        //         $tot = $prodottoSelezionato->quantity * $prodottoSelezionato->price_uni;
        //         $totale5 += $tot;
                
        //     }
        // }
        // array_push($totale , $totale5);

        // $ordini6 = Header::where('data' ,date('Y-m-d',strtotime("-1 days")))->get();
        // $totale6 = 0;
        // foreach( $ordini6 as $ordine){
        //     $prodottiSelezionati = SelectedProduct::all()->where('header_id' , $ordine->id);
        //     foreach($prodottiSelezionati as $prodottoSelezionato){
        //         $tot = $prodottoSelezionato->quantity * $prodottoSelezionato->price_uni;
        //         $totale6 += $tot;
        //     }
        // }
        // array_push($totale , $totale6);
        
        // $ordini7 = Header::where('data' ,date("Y-m-d"))->get();
        // $totale7 = 0;
        // foreach( $ordini7 as $ordine){
        //     $prodottiSelezionati = SelectedProduct::all()->where('header_id' , $ordine->id);
        //     foreach($prodottiSelezionati as $prodottoSelezionato){
        //         $tot = $prodottoSelezionato->quantity * $prodottoSelezionato->price_uni;
        //         $totale7 += $tot;
                
        //     }
        // }
        // array_push($totale , $totale7);
        
        

      
  
}
