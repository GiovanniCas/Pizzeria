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
        $header->stato = Header::STATO_IN_CONSEGNA;
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
        $header->stato = Header::STATO_CONSEGNATO;
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
                        $q = $q->all();
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
        
        //$prodottiSelezionati = SelectedProduct::all();
        if(empty(session('searchOrder')) && empty(session('stato'))){
            if(Auth::user()->ruolo == User::GESTORE){
              //  dd(Auth::user());
                $orders = Header::all()->where('stato' , '<>' , 0)->paginate(9);
                return view('listaOrdini' , compact('orders'));
            }
            if(Auth::user()->ruolo == User::CUOCO){
                $orders = Header::where('stato' , Header::STATO_IN_PREPARAZIONE)->paginate(9);
                return view('listaOrdini' , compact('orders'));
            }
            if(Auth::user()->ruolo == User::FATTORINO){
                $orders = Header::where('stato' , Header::STATO_IN_CONSEGNA)->paginate(9);
                return view('listaOrdini' , compact('orders'));
            }
            
            
        }else{
            $search = session('searchOrder');
            $stato = session('stato');
            $q = Header::query();

            if($search){
                $q = $q->where('name','LIKE','%'.$search.'%');
            }

            if($stato){
                foreach($stato as $m){
                    if($m === "Tutte"){
                        $q = $q->where('stato' , '<>' , 'Tutte');
                    } else{
                    $q = $q->where('name','LIKE','%'.$search.'%')
                        ->where('stato', $m)
                        ->orWhere('stato', $m);
                    }
                }  
            }
            $q = $q->paginate(9);
            $orders = $q;
        }

        $users = User::all();
        return view('listaOrdini' , compact('orders'));//->with(compact('prodottiSelezionati'));
            
    }

    public function searchOrder(Request $request){
       
        $search = $request->search;
        $stato = $request->stato;

        session()->put('searchOrder' , $search);
        session()->put('stato' , $stato);

        return redirect(route('orderList'));
    }

    public function report(){

        if (Gate::denies('Gestore')) {
            abort(403);
        } 
      
        $from = date('Y-m-d',strtotime("-6 days"));
        $to = date('Y-m-d');
        $totali_per_giorno = [];
        $date = [];
        $ordini= Header::all()->whereBetween('data' , [$from , $to])->where('stato' , '<>' , 0 )->sortBy('data')->groupBy('data');
       
        
        foreach($ordini as  $ordine){
            $totale_giornaliero = $ordine->sum('tot');
            array_push($totali_per_giorno , $totale_giornaliero);
            
            $data = $ordine->value('data');
            array_push($date, $data);
            
        }
       
        return view('report' , compact('date'))->with(compact('totali_per_giorno'));
    }
 
}
