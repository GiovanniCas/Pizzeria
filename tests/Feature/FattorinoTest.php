<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Header;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FattorinoTest extends TestCase
{
    use RefreshDatabase; 
    public function test_accettazione_consegna_fattorino(){
        $user1 = User::factory()->create();
        $user2 = User::factory()->create(['ruolo' => 3]);

        $header = Header::factory()->create([
            'user_id' => $user1->id,
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
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);  
        $response = $this->actingAs($user2)->put(route('acceptOrder' , $header->id) , [              
            "user_id" => $user2->id,          
        ]);
        $this->assertDatabaseHas('headers' , [
            'id' => $header->id,
            "user_id" => $user2->id,
        ]);
        $response = $this->get(route('orderList'));
        $response->assertStatus(200);

        $response = $this->actingAs($user1)->put(route('acceptOrder' , $header->id) , [              
            "user_id" => $user1->id,          
        ]);
        $response->assertStatus(403);
    }

    public function test_consegna_avvenuta(){
        $user = User::factory()->create(['ruolo' => 3]);
        $user2 = User::factory()->create();
        $header = Header::factory()->create(['user_id' => $user->id]);
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
        $response = $this->actingAs($user)->put(route('deliveredOrder' , $header->id) , [              
           
            "accettazione" => 3,          
        ]);
        $this->assertDatabaseHas('headers', [
            'id'=> $header->id,
            'accettazione' => 3,
        ]);
        $response = $this->get(route('orderList'));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->put(route('deliveredOrder' , $header->id) , [              
           
            "accettazione" => 3,          
        ]);
        $response->assertStatus(403);

    }    
}
