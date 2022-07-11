<?php

namespace App\Http\Controllers\Backend;

use App\Models\Image;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



class ProductController extends Controller
{
    public function pizza(){
        
        
        $categories = Category::all();
        $images = Image::all();
        if(Auth::user()){
            if(empty(session('searchProduct')) && empty(session('category'))){
                $products = Product::paginate(7);
            }
            if(session('category_id')){
            
                $id = session('category_id');
                $products = Product::all()->where('category_id' , $id)->paginate(4);
                
                session()->forget('category_id');
                
            }else{
                
                $q = Product::query();
                $search = session('searchProduct');
                $category = session('category');
                if(($search) && ($category)){
                    foreach($category as $cat){
                     
                        if($cat === "Tutte"){
                            $q = $q->where('name','LIKE','%'.$search.'%')
                            ->where('category_id' , '<>' , 'Tutte');
                        }else{
                            $q = $q->where('name','LIKE','%'.$search.'%')
                                ->where('category_id', $cat)
                                ->where('category_id', $cat);
                            }
                        }  
                    }
                    
                    if(($search) && (!$category)){
                        $q = $q->where('name','LIKE','%'.$search.'%');
                        
                    }
                    
                    if((!$search) && ($category)){
                    
                        foreach($category as $cat){
                        
                            if($cat === "Tutte"){
                                $q = $q->where('category_id' , '<>' , 'Tutte');
                            } else{
                            
                                $q = $q->where('category_id', $cat)
                                    ->orWhere('category_id', $cat);
                            }
                        }  
                    }
                    
                    
                    $q = $q->paginate(7);
                    $products = $q;
                }    

        }else{
            if(empty(session('searchProduct')) && empty(session('category'))){
                $products = Product::paginate(4);
            }
            if(session('category_id')){
            
                $id = session('category_id');
                $products = Product::all()->where('category_id' , $id)->paginate(4);
                
                session()->forget('category_id');
                
            }else{
                
                $q = Product::query();
                $search = session('searchProduct');
                $category = session('category');
                if(($search) && ($category)){
                    foreach($category as $cat){
                     
                        if($cat === "Tutte"){
                            $q = $q->where('name','LIKE','%'.$search.'%')
                            ->where('category_id' , '<>' , 'Tutte');
                        }else{
                            $q = $q->where('name','LIKE','%'.$search.'%')
                                ->where('category_id', $cat)
                                ->where('category_id', $cat);
                            }
                        }  
                    }
                    
                    if(($search) && (!$category)){
                        $q = $q->where('name','LIKE','%'.$search.'%');
                        
                    }
                    
                    if((!$search) && ($category)){
                    
                        foreach($category as $cat){
                        
                            if($cat === "Tutte"){
                                $q = $q->where('category_id' , '<>' , 'Tutte');
                            } else{
                            
                                $q = $q->where('category_id', $cat)
                                    ->orWhere('category_id', $cat);
                            }
                        }  
                    }
                    
                    
                    $q = $q->paginate(4);
                    $products = $q;
                }    

        }
            
       
        
        return view("pizza" , compact('products'))->with(compact('categories'))->with(compact('images'));
    }

    public function newProduct(){
        $categories = Category::all();
        if (Gate::denies('Gestore')) {
            abort(403);            
        } 
        return view("newProduct", compact("categories"));
    }

    public function submitProduct(Request $request){
        if (Gate::denies('Gestore')) {
            abort(403);            
        } 
        $categories = Category::all();
        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->category_id = $request->input('category_id');

        $product->save();
        $id = $product->id;
        session()->put('product_id', $id);
        
        $images = $request->file('images');
        if($images){
            foreach($images as $image) 
            {
                $img = new Image();
                $img->product_id = $product->id;
                $img->save();
                
                $filename = $img->id.'.img';
                $img->img = $filename;
                $img->save();

                $destinationPath = 'storage/img/';
                $image->move($destinationPath, $filename);
            }
            
        }
        return redirect(route("welcome"));
    }

    public function formModify(Product $product , Image $image){
        $categories = Category::all();
        $images = Image::where('product_id' , $product->id)->get();
        if (Gate::denies('Gestore')) {
            abort(403); 
            return view("welcome" , compact("categories"))->with("message" , "Non sei Autorizzato ad aggiungere prodotti!");
        } 
        return view('modificaProdotto', compact('product'))->with(compact('categories'))->with(compact('images'));
    }

    public function modifyProduct(Request $request , Product $product ){
        if (Gate::denies('Gestore')) {
            abort(403);            
        } 

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->save();

        $images = $request->file('images');
        ($images);
        if($images){
            foreach($images as $image) 
            {
                $img = new Image();
                $img->product_id = $product->id;
                $img->save();
                
                
                $filename = $img->product_id.'.img';
                $img->img = $filename;
                $img->save();


                $destinationPath = 'storage/img/';
                $image->move($destinationPath, $filename);
            }
            
        }
          
        return redirect(route('pizza'));
    }

    public function deleteProduct(Product $product){
        if (Gate::denies('Gestore')) {
            abort(403);            
        } 
        $product->delete();
    
        return redirect(route('pizza'));
    }

    public function deleteImg(Image $img){
        if (Gate::denies('Gestore')) {
            abort(403);            
        } 
        $img->delete();
    
        return redirect()->back();
    }

    public function search(Request $request){
        $search = $request->search;
        $category = $request->category_id;
        session()->put('searchProduct' , $search);
        session()->put('category' , $category);
        return redirect(route('pizza'));
    }
       
    public function addCategory(){
        if (Gate::denies('Gestore')) {
            abort(403);
        } 
        return view('addCategory');
    }

    public function addNewCategory(Request $request){
        if (Gate::denies('Gestore')) {
            abort(403);
        } 
        $category = new Category();
        $category->name = $request->input('name');
        $category->img = $request->file('img')->store('public/img');
        $category->description = $request->input('description');
    
        $category->save();
    
        return redirect(route('welcome'));
    }

    public function modifyCategory(Category $category){
        if (Gate::denies('Gestore')) {
            abort(403);
        } 
        return view('modifyCategory', compact('category'));
    }
    
    public function modifyNewCategory(Category $category , Request $request){
        if (Gate::denies('Gestore')) {
            abort(403);
        } 
        $category->name = $request->name;
        $category->description = $request->description;
        $category->img = $request->file('img')->store('public/img');
        
        $category->save();
        return redirect(route('welcome'));
    }

    public function deleteCategory(Category $category){
        if (Gate::denies('Gestore')) {
            abort(403);
        } 
        $category->delete();

        return redirect(route('welcome'));
    }
}
