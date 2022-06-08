<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orderForm(){
        return view("orderForm");
    }

    public function orderSubmit(Request $request){
        dd($request->all());
        $order = new Order();
        $order->name = $request->input('name');
        $order->surname = $request->input('surname');
        $order->citta = $request->input('citta');
        $order->indirizzo = $request->input('indirizzo');
        $order->cap = $request->input('cap');
        $order->rag = $request->input('rag');
        $order->data = $request->input('data');
        $order->time = $request->input('time');
        
        
        $order->save();
        return view("welcome");

    }
}
