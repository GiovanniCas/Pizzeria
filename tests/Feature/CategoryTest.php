<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase; 
    public function test_vista_nuova_categoria(){
        $user = User::factory()->create(['ruolo' => 1]);
        $response = $this->actingAs($user)->get(route('addCategory'));
        $response->assertStatus(200);
    
        $user2 = User::factory()->create();
        $response = $this->actingAs($user2)->get(route('addCategory'));
        $response->assertStatus(403);
    }


    public function test_aggiungi_categoria(){
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create(['ruolo' => 2]);
        Storage::fake('avatars');
 
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)->post(route("addNewCategory") ,
        [
            'name' => "Pizze",
            'description' => "ciao",
            'img' =>  $file,
        ]);
        $this->assertDatabaseHas('categories' , [
            'name' => 'Pizze',
        ]);
        $response = $this->get(route('welcome'));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->post(route("addNewCategory") ,
        [
            'name' => "Pizze",
            'description' => "ciao",
            'img' =>  $file,
        ]);

        $response->assertStatus(403);
    }

    public function test_vista_modifica_categoria(){
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->get(route('modifyCategory' , $category->id));
        $response->assertStatus(200);

        
        $response = $this->actingAs($user2)->get(route('modifyCategory' , $category->id));
        $response->assertStatus(403);
    }
        
    public function test_modifica_categoria(){
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create();
        $category = Category::factory()->create();
 
        $file = UploadedFile::fake()->image('avatar.jpg');
            
        $response = $this->actingAs($user)->put(route("modifyNewCategory", $category->id ),
        [
            'name' => "Pizzette",
            'description' => "ciaone",
            'img' =>  $file,
            
        ]);
        $this->assertDatabaseHas('categories' , [
            'id' => $category->id,
            'name' => 'Pizzette',
        ]);
        $response = $this->get(route('welcome'));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->get(route('modifyCategory' , $category->id),
        [
            'name' => "Pizzette",
            'description' => "ciaone",
            'img' =>  $file,
            
        ]);
        $response->assertStatus(403);
    }

    public function test_elimina_categoria(){
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create();
        $category = Category::factory()->create();
        $category2 = Category::factory()->create();
            
        $response = $this->actingAs($user)->delete(route("deleteCategory", $category->id));
        $this->assertDatabaseMissing('categories' ,[
            'id' => $category->id,
        ]);
        $response = $this->get(route('welcome'));
        $response->assertStatus(200);
        
        $response = $this->actingAs($user2)->delete(route("deleteCategory", $category2->id));
        $response->assertStatus(403);

    }

}
