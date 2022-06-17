<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Header;
use Illuminate\Http\Request;
use App\Models\SelectedProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class RevisorController extends Controller
{
    public function revisor(){
        if (Gate::denies('Cuoco') && Gate::denies('Gestore')) {
            return view("welcome")->with("message" , "Non sei Autorizzato ");
        } 
        $headers= Header::all()->sortBy('data');
        $prodottiSelezionati = SelectedProduct::all();
      
        return view("revisore", compact('headers'))->with(compact('prodottiSelezionati'));
    }

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
        $user->password = $request->input('password');
        $user->mansione = $request->input('mansione');

        $user->save();
    
        return redirect(route('staff'));
    }

    public function fattorino(){
        if (Gate::denies('Fattorino') && Gate::denies('Gestore')) {
            abort(403);
        } 
        $headers = Header::all();
        return view('fattorino' , compact('headers'));
    }

    public function updateOrder(Header $header){
      
        $header->accettazione = 2;
        $header->save();
        return redirect(route('revisor'));
    }

    public function consegne(){
        if (Gate::denies('Fattorino') && Gate::denies('Gestore')) {
            abort(403);
        } 
        $headers = Header::all();
        return view('consegne' , compact('headers'));
    }

    public function acceptOrder(Header $header ){
        $header->user_id = Auth::user()->id;
        $header->save();
        return redirect(route('consegne'))->with(compact('header'));
    }

    public function deliveredOrder(Header $header){
        $header->accettazione = 3;
        $header->save();
        return redirect(route('fattorino'));
    }

    public function utenti(){
        $users = User::all();
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
        $user->mansione = $request->mansione;
        $user->save();

        return redirect(route('utenti'));

    }

    public function deleteUser(User $user ){

        $user->delete();
        return redirect(route('utenti'));
    }

    public function searchUser(Request $request){
        $users = User::all();
        $search = $request->search;
        $mansione = $request->mansione;
        
        

        if((!is_null($search)) && (is_null($mansione))){
            
            $users = User::where('name','LIKE','%'.$search.'%')->get();

        } elseif((is_null($search)) && (!is_null($mansione))){
            $users = User::where('mansione', $mansione)->get();
        }
        

        return view('utenti' , compact('users'));
    }
}
