<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Header;
use Illuminate\Http\Request;
use App\Models\SelectedProduct;


class RevisorController extends Controller
{
    public function revisor(){
        $headers= Header::all()->sortBy('data');
        $prodottiSelezionati = SelectedProduct::all();
      
        return view("revisore", compact('headers'))->with(compact('prodottiSelezionati'));
    }

    public function staff(){
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
        $orders = Header::all();
        return view('fattorino' , compact('orders'));
    }

    public function updateOrder(Header $header){
        $header->accettazione = 2;
        $header->save();
        return redirect(route('revisor'));
    }

    public function consegne(){
        $orders = Header::all();
        return view('consegne' , compact('orders'));
    }


}
