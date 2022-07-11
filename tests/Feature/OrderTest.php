<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Header;
use App\Models\Product;
use App\Models\Category;
use App\Models\SelectedProduct;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_vista_carrello(){
        $response = $this->get(route('cart'));
        $response->assertStatus(200);
    }  


    public function test_aggiungi_al_carrello(){

        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $product2 = Product::factory()->create(['category_id' => $category->id]);
        $product3 = Product::factory()->create(['category_id' => $category->id]);
        $user = User::factory()->create();
        $header = Header::factory()->create(['user_id' => $user->id]);
        session()->put('header_id', $header->id);

    
        $response = $this->post(route('addCart') ,  [

            'quantity' => [ $product->id => 4, $product2->id => 0 , $product3->id => 2],
        ]);

        $response->assertSessionHas('header_id');
        $this->assertDatabaseHas('selected_products' , 
        [ 
           'header_id' => session('header_id')
        ]);
        $response = $this->get(route('cart'));
        $response->assertStatus(200);

    } 

    public function test_modifica_prodotti_nel_carrello() {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $user = User::factory()->create();
        $header = Header::factory()->create(['user_id' => $user->id]);
        session()->put('header_id' , $header->id);
        $selectedProduct = SelectedProduct::factory()->create([
            'header_id' => session('header_id'),
            'product_id' => $product->id
        ]);
        $response = $this->put(route("modificaQuantita" , $selectedProduct) , [
            'quantity' => 21
        ]);
        $this->assertDatabaseHas('selected_products' , [
            'id' => $selectedProduct->id,
            'quantity' => 21,
        ]);

        
    }

        
    public function test_invia_ordine() {
        $user = User::factory()->create();
        $header = Header::factory()->create(['user_id' => $user->id]);
        session()->put('header_id' , $header->id);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $selectedProduct = SelectedProduct::factory()->create([
            'product_id' => $product->id,
            'header_id'=> $header->id,
        ]);
        $response = $this->post(route('orderSubmit' , $header->id) , [              
            "name" => "paolo",
            "surname" =>"rossi",
            "email" => "ciaone9876@hotmail.it",
            "indirizzo" => "ciao",
            "cap" => "ciao",
            "citta"=> "londra",
            "data"=> "2022-06-29",
            "time"=> "22:30",
            "accettazione" => 1,
        ]);
       
        
        $response = $this->get(route('welcome'));
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('headers' , [
            'id' => $header->id,
            'name' => 'paolo'
            
        ]);
    }
    
    public function test_pagina_guadagno(){

        $user = User::factory()->create();
        $header = Header::factory()->create(['user_id' => $user->id]);
        session()->put('header_id' , $header->id);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['price' => 22 ,'category_id' => $category->id]);
        $selectedProduct = SelectedProduct::factory()->create([
            'product_id' => $product->id,
            'header_id'=> $header->id,
            'quantity' => 11,
            'price_uni' => $product->price
        ]);
        $selectedProduct = SelectedProduct::factory()->create([
            'product_id' => $product->id,
            'header_id'=> $header->id,
            'quantity' => 9,
            'price_uni' => $product->price
        ]);
      
        $response = $this->post(route('orderSubmit' , $header->id) , [              
            "name" => "paolo",
            "surname" =>"rossi",
            "email" => "ciaone9876@hotmail.it",
            "indirizzo" => "ciao",
            "cap" => "ciao",
            "citta"=> "londra",
            "data"=> "2022-07-05",
            "time"=> "22:30",
            "accettazione" => 1,
            
        ]);

        $user2 = User::factory()->create();
        $header2 = Header::factory()->create(['user_id' => $user2->id]);
        session()->put('header_id' , $header2->id);
    
        $selectedProduct = SelectedProduct::factory()->create([
            'product_id' => $product->id,
            'header_id'=> $header2->id,
            'quantity' => 12,
            'price_uni' => $product->price
        ]);
        
        $response = $this->post(route('orderSubmit' , $header2->id) , [              
            "name" => "paolo",
            "surname" =>"rossi",
            "email" => "ciaone9876@hotmail.it",
            "indirizzo" => "ciao",
            "cap" => "ciao",
            "citta"=> "londra",
            "data"=> "2022-07-06",
            "time"=> "22:30",
            "accettazione" => 1,
            
        ]);   
        
        $user3 = User::factory()->create();
        $header3 = Header::factory()->create(['user_id' => $user3->id]);
        session()->put('header_id' , $header3->id);
        
        $selectedProduct = SelectedProduct::factory()->create([
            'product_id' => $product->id,
            'header_id'=> $header3->id,
            'quantity' => 13,
            'price_uni' => $product->price
        ]);
        
        $response = $this->post(route('orderSubmit' , $header3->id) , [              
            "name" => "paolo",
            "surname" =>"rossi",
            "email" => "ciaone9876@hotmail.it",
            "indirizzo" => "ciao",
            "cap" => "ciao",
            "citta"=> "londra",
            "data"=> "2022-07-07",
            "time"=> "22:30",
            "accettazione" => 1,
            
        ]);   
        
        $user4 = User::factory()->create();
        $header4 = Header::factory()->create(['user_id' => $user4->id]);
        session()->put('header_id' , $header4->id);
      
        $selectedProduct = SelectedProduct::factory()->create([
            'product_id' => $product->id,
            'header_id'=> $header4->id,
            'quantity' => 14,
            'price_uni' => $product->price
        ]);
       
        $response = $this->post(route('orderSubmit' , $header4->id) , [              
            "name" => "paolo",
            "surname" =>"rossi",
            "email" => "ciaone9876@hotmail.it",
            "indirizzo" => "ciao",
            "cap" => "ciao",
            "citta"=> "londra",
            "data"=> "2022-07-08",
            "time"=> "22:30",
            "accettazione" => 1,
            
        ]);   
        
        $user5 = User::factory()->create();
        $header5 = Header::factory()->create(['user_id' => $user5->id]);
        session()->put('header_id' , $header5->id);
      
        $selectedProduct = SelectedProduct::factory()->create([
            'product_id' => $product->id,
            'header_id'=> $header5->id,
            'quantity' => 15,
            'price_uni' => $product->price
        ]);
        
        $response = $this->post(route('orderSubmit' , $header5->id) , [              
            "name" => "paolo",
            "surname" =>"rossi",
            "email" => "ciaone9876@hotmail.it",
            "indirizzo" => "ciao",
            "cap" => "ciao",
            "citta"=> "londra",
            "data"=> "2022-07-09",
            "time"=> "22:30",
            "accettazione" => 1,
            
        ]);   
        
        $user6 = User::factory()->create();
        $header6 = Header::factory()->create(['user_id' => $user6->id]);
        session()->put('header_id' , $header6->id);
      
        $selectedProduct = SelectedProduct::factory()->create([
            'product_id' => $product->id,
            'header_id'=> $header6->id,
            'quantity' => 17,
            'price_uni' => $product->price
        ]);
      
        $response = $this->post(route('orderSubmit' , $header6->id) , [              
            "name" => "paolo",
            "surname" =>"rossi",
            "email" => "ciaone9876@hotmail.it",
            "indirizzo" => "ciao",
            "cap" => "ciao",
            "citta"=> "londra",
            "data"=> "2022-07-10",
            "time"=> "22:30",
            "accettazione" => 1,
            
        ]);   
        
        $user7 = User::factory()->create();
        $header7 = Header::factory()->create(['user_id' => $user7->id]);
        session()->put('header_id' , $header7->id);
    
        $selectedProduct = SelectedProduct::factory()->create([
            'product_id' => $product->id,
            'header_id'=> $header7->id,
            'quantity' => 18,
            'price_uni' => $product->price
        ]);
        
        $response = $this->post(route('orderSubmit' , $header7->id) , [              
            "name" => "paolo",
            "surname" =>"rossi",
            "email" => "ciaone9876@hotmail.it",
            "indirizzo" => "ciao",
            "cap" => "ciao",
            "citta"=> "londra",
            "data"=> "2022-07-11",
            "time"=> "22:30",
            "accettazione" => 1,
            
        ]);
       

        $response = $this->get(route('report'));
        $response->assertStatus(200);
        
       

    }
}
