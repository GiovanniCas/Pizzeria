<?php

namespace App\Http\Controllers;

use App\Models\Header;
use App\Mail\AdminMail;
use App\Models\Product;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\Models\SelectedProduct;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
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

    public function destroyOrder(Header $header)
    {
        $header->delete();
    
        return redirect(route("revisor"));
    }
}
