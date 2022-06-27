<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Header;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SelectedProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;


class RevisorController extends Controller
{
    // public function revisor(){
    //     if (Gate::denies('Cuoco') && Gate::denies('Gestore')) {
    //         abort(403);
    //     } 
    //     $headers= Header::all()->sortBy('data');
    //     $prodottiSelezionati = SelectedProduct::all();
      
    //     return view("revisore", compact('headers'))->with(compact('prodottiSelezionati'));
    // }

    public function staff(){
        if (Gate::denies('Gestore')) {
            abort(403);
            //return view("welcome")->with("message" , "Non sei Autorizzato ad aggiungere prodotti!");
        }      
        return view('staff');
    }

    public function addStaff(Request $request){
        
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
      
        $header->accettazione = 2;
        $header->save();
        return redirect(route('orderList'));
    }

    public function destroyOrder(Header $header)
    {
        $header->delete();
    
        return redirect(route('orderList'));
    }

    //fattorino che accetta
    public function acceptOrder(Header $header ){
        $header->user_id = Auth::user()->id;
        $header->save();
        return redirect(route('orderList'))->with(compact('header'));
    }

    public function deliveredOrder(Header $header){
        $header->accettazione = 3;
        $header->save();
        return redirect(route('orderList'));  //prima c'era fattorino
    }

    public function utenti(){
        
        if(empty(session('searchUser')) && empty(session('ruolo'))){
            $users = User::paginate(4);
        }else{
            $search = session('searchUser');
            $ruolo = session('ruolo');
            $q = User::query();

            if($search){
                $q = $q->where('name','LIKE','%'.$search.'%');
            }
    
            if($ruolo){
                foreach($ruolo as $m){
                 
                    if($m === "Tutte"){
                        $q = $q->where('ruolo' , '<>' , 'Tutte');
                    } else{
                    //select * from utenti where name LIKE "%seqarch%" AND ( 
                    $q = $q->where('name','LIKE','%'.$search.'%')
                        ->where('ruolo', $m)
                        ->orWhere('ruolo', $m);
                    }
                }  
            }
        
            $q = $q->paginate(4);
            $users = $q;
        }
        
        session()->forget('users');

        return view('utenti', compact('users'));
    }

    public function updateUtente(User $user){
        return view('modificaUtente' , compact('user'));
    }

    public function updateUser(User $user , Request $request){
    
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->ruolo = $request->ruolo;
        $user->save();

        return redirect(route('utenti'));

    }

    public function deleteUser(User $user ){

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
        
        if(empty(session('searchOrder')) && empty(session('accettazione'))){
            $orders = Header::paginate(5);
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
                    //select * from utenti where name LIKE "%seqarch%" AND ( 
                    $q = $q->where('name','LIKE','%'.$search.'%')
                        ->where('accettazione', $m)
                        ->orWhere('accettazione', $m);
                    }
                }  
            }
        
            $q = $q->paginate(5);
            $orders = $q;
        }

        return view('listaOrdini' , compact('orders'));//->with(compact('prodottiSelezionati'));
    }

    public function searchOrder(Request $request){
       
        $search = $request->search;
        $accettazione = $request->accettazione;
        session()->put('searchOrder' , $search);
        session()->put('accettazione' , $accettazione);

        return redirect(route('orderList'));
    }

  
}
