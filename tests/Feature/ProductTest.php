<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Image;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase; 

    public function test_lista_prodotti(){
        $response = $this->get(route('pizza'));
        $response->assertStatus(200);
        
        $response = $this->withSession(['category_id' => 1])->get(route('pizza'));
        $response->assertStatus(200);

        $response = $this->withSession(['searchProduct' => "marghe"])->get(route('pizza'));
        $response->assertStatus(200);
    }

    public function test_vista_inserisci_nuovo_prodotto(){
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create();
        $response = $this->actingAs($user)->get(route('newProduct'));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->get(route('newProduct'));
        $response->assertStatus(403);

    }

    public function test_aggiungi_prodotto(){
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create();
        $category = Category::factory()->create();
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->actingAs($user)->post(route("submitProduct") ,
        [
            'name' => "Mario",
            'description' => "ciao",
            'category_id' => $category->id,
            'price' => 6.00, 
            'images' => [$file], 
                      
        ]);

        $this->assertDatabaseHas('products' , [
            'name' => 'Mario',
            'description' => 'ciao'
        ]);

        $this->assertDatabaseHas('images' , [
            'product_id' => session('product_id')
        ]);

        // $response = $this->get(route('getImages'));
        // $response->assertStatus(200);

        $response = $this->actingAs($user2)->post(route('submitProduct'),
        [
            'name' => "Mario",
            'description' => "ciao",
            'category_id' => $category->id,
            'price' => 6.00,
        ]);
        $response->assertStatus(403);
    }


    public function test_vista_modifica_prodotto(){
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        session()->put('product_id' , $product->id);
        $img = Image::factory()->create(['product_id' => $product->id]);
        $response = $this->actingAs($user)->get(route("formModify" , $product->id));
        $response->assertStatus(200);

        $response = $this->actingAs($user2)->get(route("formModify" , $product->id));
        $response->assertStatus(403);
        $response = $this->get(route('welcome'));
        $response->assertStatus(200);

        
    }


    public function test_modifica_prodotto(){
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $img = Image::factory()->create(['product_id' => $product->id]);
        $response = $this->actingAs($user)->put(route("modifyProduct" , $product->id), 
        [
            'name' => "Umberto",
            'description' => "ciaone",
            'category_id' => 2,
            'price' => 6.00,
            'images' => [$file], 

        ]);
        $this->assertDatabaseHas('products' , [
            'id' => $product->id,
        ]);

        $this->assertDatabaseHas('images' , [
            'product_id' => $product->id,
        ]);

    

        $response = $this->actingAs($user2)->put(route("modifyProduct" , $product->id), 
        [
            'name' => "Umberto",
            'description' => "ciaone",
            'category_id' => 2,
            'price' => 6.00,
        ]);
        $response->assertStatus(403);
    }

    public function test_elimina_prodotto(){
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $product2 = Product::factory()->create(['category_id' => $category->id]);
        $response = $this->actingAs($user)->delete(route("deleteProduct" , $product->id));
        $this->assertDatabaseMissing('products' , [
            'id' => $product->id,
            ]);
        
        $response = $this->actingAs($user2)->delete(route("deleteProduct" , $product2->id));
        $response->assertStatus(403);

    }

    public function test_elimina_immagine_prodotto(){
        $user = User::factory()->create(['ruolo' => 1]);
        $user2 = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $img = Image::factory()->create(['product_id' => $product->id]);
        $img2 = Image::factory()->create(['product_id' => $product->id]);
     
        $response = $this->actingAs($user)->delete(route("deleteImg" , $img->id));
        $this->assertDatabaseMissing('images' , [
            'id' => $img->id,
            ]);
        
        $response = $this->actingAs($user2)->delete(route("deleteImg" , $img2->id));
        $response->assertStatus(403);

    }


}
