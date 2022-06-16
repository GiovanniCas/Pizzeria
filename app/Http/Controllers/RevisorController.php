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
            return view("welcome")->with("message" , "Non sei Autorizzato ");
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
            return view("welcome")->with("message" , "Non sei Autorizzato ");
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
}
