<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class ProductController extends Controller
{

    public function newProduct(){
       
        if (Gate::denies('Gestore')) {
            return view("welcome")->with("message" , "Non sei Autorizzato ad aggiungere prodotti!");
        } 
        $categories = Category::all();
        return view("newProduct", compact("categories"));
    }
    public function submitProduct(Request $request){

       
      
        $categories = Category::all();
        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category_id');

        
        $product->save();
        
        return view("newProduct" , compact('categories')) ;

    }

    public function formModify(Product $product){
        if (Gate::denies('Gestore')) {
            return view("welcome")->with("message" , "Non sei Autorizzato ad aggiungere prodotti!");
        } 
        $categories = Category::all();
        return view('modificaProdotto', compact('product'))->with(compact('categories'));
    }

    public function modifyProduct(Request $request , Product $product){
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        
        $product->save();
  
        return redirect(route('pizza'));
    }
}
