<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Header;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CuocoTest extends TestCase
{
    use RefreshDatabase; 
    public function test_cuoco_toglie_la_pizza(){
        $user = User::factory()->create(['ruolo' => 2]);
        $user2 = User::factory()->create(['ruolo' => 3]);
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
     
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $response = $this->actingAs($user)->post(route('updateOrder' , $header->id) , [                
            "accettazione" => 2,
        ]);
        $this->assertDatabaseHas('headers' , [
            'id' => $header->id,
        ]);
        $header2 = Header::factory()->create(['user_id' => $user->id]);
        $response = $this->post(route('orderSubmit' , $header2->id) , [              
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

        $response = $this->get(route('orderList'));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->post(route('updateOrder' , $header2->id) , [                
            "accettazione" => 2,
        ]);
        $response->assertStatus(405);

        
    }

    public function test_cuoco_elimina_ordine(){
        $user = User::factory()->create(['ruolo' => 2]);
        $user2 = User::factory()->create(['ruolo' => 3]);
        $header = Header::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);      
        $response = $this->actingAs($user)->delete(route('destroyOrder' , $header->id));
        $this->assertDatabaseMissing('headers' , [
            'id' => $header->id,
        ]);
        $response = $this->get(route('orderList'));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->delete(route('destroyOrder' , $header->id));
        $this->assertDatabaseMissing('headers' , [
            'id' => $header->id,
        ]);
    }
}
