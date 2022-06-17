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

    public function deleteProduct(Product $product){
        $product->delete();
    
        return redirect(route('pizza'));
    }

    public function search(Request $request){
        $categories = Category::all();
        $search = $request->search;
        $category = $request->category_id;
        

        if((!is_null($search)) && (is_null($category))){
            
            $products = Product::where('name','LIKE','%'.$search.'%')->get();

        } elseif((is_null($search)) && (!is_null($category))){
            $products = Product::where('category_id', $category)->get();
        }
        else {
            $products = Product::where('name','LIKE','%'.$search.'%')->where('category_id', $category)->get();
        }

        return view('pizza' , compact('products'))->with(compact('categories'));
    }
}
