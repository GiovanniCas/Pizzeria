<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Image;
use App\Models\Header;
use App\Models\Product;
use App\Models\Category;
use App\Mail\OrderShipped;
use Illuminate\Http\Request;
use App\Models\SelectedProduct;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class UserTest extends TestCase
{

    use RefreshDatabase; 
    
    //UTENTI
    public function test_vista_pagina_utenti(){
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create();
        $response = $this->actingAs($user)->get(route('staff'));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->get(route('staff'));
        $response->assertStatus(403);
        
    }

    public function test_vista_pagina_aggiungi_utenti(){
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create();
        $response = $this->actingAs($user)->get(route('staff'));
        $response->assertStatus(200);
       
        $response = $this->actingAs($user2)->get(route('staff'));
        $response->assertStatus(403);
        
    }

    public function test_aggiungi_utente(){
        $user = User::factory()->create(['ruolo' => 1]);
        $response = $this->actingAs($user)->post(route('addStaff') ,
        [
            'name' => "Peppino",
            'surname' => "Castagna",
            'email' => "paprino@paerino.it",
            'password' => Hash::make('password'),
            'ruolo' => 3,
        ]);
        
        $this->assertDatabaseHas('users' , [
            'name' => 'Peppino',
            'surname' => "Castagna",
        ]);
        $response = $this->get(route('staff'));
        $response->assertStatus(200);

        $user2 = User::factory()->create(['ruolo' => 2]);
        $response = $this->actingAs($user2)->post(route('addStaff') ,
        [
            'name' => "Peppino",
            'surname' => "Casta",
            'email' => "parino@paerno.it",
            'password' => Hash::make('password'),
            'ruolo' => 3,
        ]);
        $response->assertStatus(403);
      
    }

    public function test_vista_form_modifica_utente(){
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $response = $this->actingAs($user)->get(route('updateUtente' , $user3->id));
        $response->assertStatus(200);
       
        $response = $this->actingAs($user2)->get(route('updateUtente' , $user3->id));
        $response->assertStatus(403);
    }

    public function test_modifica_utente(){
        $user = User::factory()->create(['name' => 'Osvaldo' , 'ruolo' => 1]);
        $user2 = User::factory()->create(['name' => 'pablo' , 'ruolo' => 2]);
        $response = $this->actingAs($user)->put(route('updateUser' , $user->id) ,
        [
            'name' => "Pepeno",
            'surname' => "Casgna",
            'email' => $user->email,
            'password' => $user->password, 
            'ruolo' => 3,
        ]); 
        $this->assertDatabaseHas('users' , [
            'id' => $user->id ,
            'name' => 'Pepeno',
        ]);
        $response = $this->get(route('utenti'));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->put(route('updateUser' , $user2->id) ,
        [
            'name' => "Miciomicio",
            'surname' => "Casgna",
            'email' => $user->email,
            'password' => $user->password, 
            'ruolo' => 3,
        ]);
        $response->assertStatus(403);
    }

    public function test_elimina_utente(){
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $response = $this->actingAs($user)->delete(route('deleteUser' , $user2->id));
        $this->assertDatabaseMissing('users' ,[
            'id' => $user2->id,
        ]);
        $response = $this->get(route('utenti'));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->delete(route('deleteUser' , $user3->id));
        $response->assertStatus(403);
    }


    //lista ordini
    public function test_vista_lista_ordini(){
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
        
        $response = $this->get(route('orderList' , $header->id ));
        
        $this->assertDatabaseHas('headers' , [
            'id' => $header->id,
        ]);
      
    }

   
  
}



