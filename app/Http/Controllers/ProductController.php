<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



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
        $id = $product->id;
        session()->put('product_id', $id);
        
        //return view("newProduct" , compact('categories')) ;
        return view("addImg");
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

        // $images = Image::all();
        // $categories = Category::all();
        $search = $request->search;
        $category = $request->category_id;
        

        if((!is_null($search)) && ($category == 'Tutte')){
            
            $products = Product::where('name','LIKE','%'.$search.'%')->get();

        } elseif((is_null($search)) && ($category == 'Tutte')){
            $products = Product::all();
        }else {
            $products = Product::where('category_id', $category)->get();
        }
        session()->put('products', $products);
        // $products =  session('products');

        return redirect(route('pizza'));//->with(compact('products'));//->with(compact('categories'))->with(compact('images'));
    }

    public function getImages(){
        return view('addImg');
    }

    public function uploadImages(Request $request){
        $categories = Category::all();
        
       
        foreach($request->file('images') as $image)
        {
            $filename = $image->getClientOriginalName();
            $destinationPath = 'storage/img/';
            $image->move($destinationPath, $filename);
            $image = new Image();
            $image->product_id = session()->get('product_id');
            $image->img = $filename;
            $image->save();
            
        }
        //dd($image);
      


        // $images = $request->file(['images']);
        // foreach($images as $image){
        //     $image = new Image();
        //     $image->product_id = session()->get('product_id');
        //     $filename = $image->getClientOriginalName();
        //     dd($filename);
        //     $image->img = $image;

        //     dd($image);


        //     $newFileName = "img/{$image->img}";

        //     $image->img = $newFileName;

        //     Storage::move($image, $newFileName);
            
        //     $image->save();
        // };

    
        return view('welcome' )->with(compact('categories'));



        
        //     $input = Input::all();
	// 	$rules = array(
	// 	    'file' => 'image|max:3000',
	// 	);

	// 	$validation = Validator::make($input, $rules);

	// 	if ($validation->fails())
	// 	{
	// 		return Response::make($validation->errors->first(), 400);
	// 	}

	// 	$file = Input::file('file');

    //     $extension = File::extension($file['name']);
    //     $directory = path('public').'uploads/'.sha1(time());
    //     $filename = sha1(time().time()).".{$extension}";

    //     $upload_success = Input::upload('file', $directory, $filename);

    //     if( $upload_success ) {
    //     	return Response::json('success', 200);
    //     } else {
    //     	return Response::json('error', 400);
    //     }
	}
       
}
