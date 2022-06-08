<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function newProduct(){
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
}
