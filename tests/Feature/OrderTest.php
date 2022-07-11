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
       
        $this->assertDatabaseHas('headers' , [
            'id' => $header->id
        ]);

        $response = $this->get(route('welcome'));
        $response->assertStatus(200);

    }
}
