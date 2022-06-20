<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\SelectedProduct;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function welcome(){
        $categories = Category::all();
        return view("welcome" , compact('categories'));
    }

    public function pizza(){
      
        $products = Product::orderBy('name' , 'asc')->paginate(4);
        $categories = Category::all();
        return view("pizza" , compact('products'))->with(compact('categories'));
    }

    public function cart(){
        $prodottiSelezionati = SelectedProduct::all()->where('header_id' , session()->get('header_id'));
              
        $totale = 0;
        foreach($prodottiSelezionati as $prodottoSelezionato){
            $tot = $prodottoSelezionato->quantity * $prodottoSelezionato->price_uni;
            $totale += $tot;
            
        }
        return view('cart' , compact ('prodottiSelezionati'))->with(compact('totale'));
    }

    public function addCategory(){
        return view('addCategory');
    }

    public function addNewCategory(Request $request){
        $category = new Category();
        $category->name = $request->input('name');
        $category->img = $request->file('img')->store('public/img');
        $category->description = $request->input('description');
    
        $category->save();
    
        return redirect(route('welcome'));
    }

    public function modifyCategory(Category $category){
        return view('modifyCategory', compact('category'));
    }
    
    public function modifyNewCategory(Category $category , Request $request){
        $category->name = $request->name;
        $category->description = $request->description;
        $category->img = $request->file('img')->store('public/img');
        
        $category->save();
        return redirect(route('welcome'));
    }

    public function deleteCategory(Category $category){
        $category->delete();

        return redirect(route('welcome'));
    }
}

