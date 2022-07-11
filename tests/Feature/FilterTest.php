<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Header;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilterTest extends TestCase
{
    use RefreshDatabase;
    public function test_cerca_prodotto_per_nome(){
        $category = Category::factory()->create();
        $product = Product::factory()->create(['name' => 'Margherita' , 'category_id' => $category->id]);
        $product1 = Product::factory()->create(['name' => 'ciao' , 'category_id' => $category->id]);
        $product2= Product::factory()->create(['name' => 'ciao2', 'category_id' => $category->id]);
        $product3 = Product::factory()->create(['name' => 'ciao3', 'category_id' => $category->id]);

        $response = $this->post(route('search' , ['search' => "ciao"]));
        $response->assertSessionHas('searchProduct');
        $response = $this->get(route("pizza" ));
        
    }    

    public function test_cerca_prodotto_per_categoria(){
        $category = Category::factory()->create();
        $category2 = Category::factory()->create();
        $category3 = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $product1 = Product::factory()->create(['category_id' =>$category2->id]);
        $product2= Product::factory()->create(['category_id' => $category3->id]);
        $product3 = Product::factory()->create(['category_id' => $category3->id]);

        $response = $this->post(route('search' , ['category_id' => 2]));
        $response->assertSessionHas('category');
        $response = $this->get(route("pizza"));

    } 

    public function test_cerca_utente_per_nome(){
        $user = User::factory()->create(['name' => 'ugo' , 'ruolo' => 1]);
        $user = User::factory()->create(['name' => 'mario']);
        $user = User::factory()->create(['name' => 'rocco']);

        $response = $this->post(route('searchUser' , ['search' => 'roc']));
        $response->assertSessionHas('searchUser');
        $response = $this->actingAs($user)->get(route("utenti"));
      

    }  

    public function test_cerca_utente_per_ruolo(){
        $user = User::factory()->create(['ruolo' => 3]);
        $user = User::factory()->create(['ruolo' => 2]);
        $user = User::factory()->create(['ruolo' => 1]);

        $response = $this->post(route('searchUser' , ['ruolo' => 1]));
        $response->assertSessionHas('ruolo');
        $response = $this->actingAs($user)->get(route("utenti" ));
      
    } 

    public function test_cerca_ordine_per_nome(){
        $user = User::factory()->create();
        $header = Header::factory()->create([
            'user_id' => $user->id,
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
        $header = Header::factory()->create([
            'user_id' => $user->id,
            "name" => "Giovanni",
            "surname" =>"Casta",
            "email" => "aiaia876@hotmail.it",
            "indirizzo" => "ciao",
            "cap" => "ciao",
            "citta"=> "londra",
            "data"=> "2022-06-29",
            "time"=> "22:30",
            "accettazione" => 1,
        ]);

        $response = $this->post(route('searchOrder' , ['search' => 'pao']));
        $response->assertSessionHas('searchOrder');
        $response = $this->get(route("orderList"));
      
    }  

    public function test_cerca_ordine_per_stato(){
        $user = User::factory()->create();
        $header = Header::factory()->create([
            'user_id' => $user->id,
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
        $header = Header::factory()->create([
            'user_id' => $user->id,
            "name" => "Giovanni",
            "surname" =>"Casta",
            "email" => "aiaia876@hotmail.it",
            "indirizzo" => "ciao",
            "cap" => "ciao",
            "citta"=> "londra",
            "data"=> "2022-06-29",
            "time"=> "22:30",
            "accettazione" => 2,
        ]);

        $response = $this->post(route('searchOrder' , ['accettazione' => 2]));
        $response->assertSessionHas('accettazione');
        $response = $this->get(route("orderList"));
    } 
}
