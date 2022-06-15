<?php

namespace App\Http\Controllers;

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
}
