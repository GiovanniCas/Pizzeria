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
    public function test_vista_creazione_utenti(){
        $user = User::factory()->create(['ruolo' => 1]);

        $response = $this->actingAs($user)->get(route('utenti'));
        
        $response->assertStatus(200);
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
        $response->assertStatus(302);
    }

    
    public function test_modifica_utente(){
        //$user = User::factory()->create(['ruolo' => 1]);
        $user = User::factory()->create(['name' => 'Osvaldo' , 'ruolo' => 1]);
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
    }

    public function test_elimina_utente(){
        $user = User::factory()->create(['ruolo' => 1]);
        $response = $this->actingAs($user)->delete(route('deleteUser' , $user->id));
        $this->assertDatabaseMissing('users' ,[
            'id' => $user->id,
        ]);
    }
        
    //CATEGORIA

    public function test_vista_nuova_categoria(){
        $user = User::factory()->create(['ruolo' => 1]);
        $response = $this->actingAs($user)->get(route('addCategory'));
    
        $response->assertStatus(200);
    }


    public function test_aggiungi_categoria(){
        $user = User::factory()->create(['ruolo' => 1]);
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
    }

    public function test_vista_modifica_categoria(){
        $user = User::factory()->create(['ruolo' => 1]);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->get(route('modifyCategory' , $category->id));
        $response->assertStatus(200);
    }
        
    public function test_modifica_categoria(){
        $user = User::factory()->create(['ruolo' => 1]);
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
    }

    public function test_elimina_categoria(){
        $user = User::factory()->create(['ruolo' => 1]);
        $category = Category::factory()->create();
            
        $response = $this->actingAs($user)->delete(route("deleteCategory", $category->id));
        $this->assertDatabaseMissing('categories' ,[
            'id' => $category->id,
        ]);
    }


    //PRODOTTI
    public function test_vista_nuovo_prodotto(){
        $user = User::factory()->create(['ruolo' => 1]);
        $response = $this->actingAs($user)->get(route('newProduct'));

        $response->assertStatus(200);
    }

    public function test_aggiungi_prodotto(){
        $user = User::factory()->create(['ruolo' => 1]);
        $category = Category::factory()->create();
        $response = $this->actingAs($user)->post(route("submitProduct") ,
            [
                'name' => "Mario",
                'description' => "ciao",
                'category_id' => $category->id,
                'price' => 6.00,
            ]);
            $this->assertDatabaseHas('products' , [
                'name' => 'Mario',
                'description' => 'ciao'
            ]);

    }

    public function test_vista_modifica_prodotto(){
        $user = User::factory()->create(['ruolo' => 1]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $response = $this->actingAs($user)->get(route("formModify" , $product->id));
        $response->assertStatus(200);
    }


    public function test_modifica_prodotto(){
        $user = User::factory()->create(['ruolo' => 1]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $response = $this->actingAs($user)->put(route("modifyProduct" , $product->id), 
        [
            'name' => "Umberto",
            'description' => "ciaone",
            'category_id' => 2,
            'price' => 6.00,
        ]);
        $this->assertDatabaseHas('products' , [
            'id' => $product->id,
        ]);
    }

    public function test_elimina_prodotto(){
        $user = User::factory()->create(['ruolo' => 1]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $response = $this->actingAs($user)->delete(route("deleteProduct" , $product->id));
        $this->assertDatabaseMissing('products' , [
            'id' => $product->id,
            ]);
    }


    //AZIONI UTENTE

    public function test_vista_carrello(){
        $response = $this->get(route('cart'));
        $response->assertStatus(200);
    }  

    public function test_aggiungi_al_carrello(){
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $user = User::factory()->create();
        $header = Header::factory()->create(['user_id' => $user->id]);
        $selectedProduct = SelectedProduct::factory()->create([
            'product_id' => $product->id,
            'header_id' => $header->id,
            'quantity' => 4,
            'price_uni' => $product->price,
        ]);
        
        $quantity = [ $product->id => $selectedProduct->quantity];
        //session()->put('header_id', $header->id);
        $response = $this->post(route('addCart') ,  [
            'quantity' => [ $product->id => $selectedProduct->quantity],
        ]);
      
        $response->assertSessionHas('header_id');

        $this->assertDatabaseHas('selected_products' , [
           'id' => $selectedProduct->id
        ]);
    } 
    
    public function test_vista_modifica_prodotti_nel_carrello(){
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $user = User::factory()->create();
        $header = Header::factory()->create(['user_id' => $user->id]);
        session()->put('header_id' , $header->id);
        $selectedProduct = SelectedProduct::factory()->create(['product_id' => $product->id]);
        $response = $this->get(route('updateQuantity' , $selectedProduct));
        $response->assertStatus(200);
    }   

    public function test_modifica_prodotti_nel_carrello(){
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $user = User::factory()->create();
        $header = Header::factory()->create(['user_id' => $user->id]);
        session()->put('header_id' , $header->id);
        $selectedProduct = SelectedProduct::factory()->create(['product_id' => $product->id]);
        $response = $this->put(route("modificaQuantita" , $selectedProduct) , [
            'quantity' => 21
        ]);
        $this->assertDatabaseHas('selected_products' , [
            'id' => $selectedProduct->id,
            'quantity' => 21,
        ]);
    } 

  
    // public function test_ordina_da_carrello(){
    //     $user = User::factory()->create();  
    //     $header = Header::factory()->create(['user_id' => $user->id]);
    //     session()->put('header_id' , $header->id );

    //     if(!session('header_id')){
    //         $user = User::factory()->create();  
    //         $header = Header::factory()->create(['user_id' => $user->id]);
    //         $id = $header->id;
    //         session()->put('header_id' , $id);
    //         $category = Category::factory()->create();
    //         $product = Product::factory()->create(['category_id' => $category->id]);
    //         $selectedProduct = SelectedProduct::factory()->create(['product_id' => $product->id]);
                        
    //         $response = $this->withSession(['header_id' => session('headers_id') ])->get(route('orderForm'));
            
  
    //     }else{
    //         $category = Category::factory()->create();
    //         $product = Product::factory()->create(['category_id' => $category->id]);
    //         $selectedProduct = SelectedProduct::factory()->create(['product_id' => $product->id]); 
    //         $response = $this->withSession(['header_id' => session('headers_id') ])->get(route('orderForm'));  
    //     }
    //     $this->assertDatabaseHas('selected_products', [
    //         'id' => $selectedProduct->id,
    //     ]);
    // }
   

    public function test_vista_order_form()
    {
        $response = $this->get(route('orderForm'));

        $response->assertStatus(200);
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
        // Mail::fake();
        
        // // Perform order shipping...
        
        // // Assert that no mailables were sent...
        // Mail::assertNothingSent();
        
        // // Assert that a mailable was sent...
        // Mail::assertSent(OrderShipped::class);
        
        // // Assert a mailable was sent twice...
        // Mail::assertSent(OrderShipped::class, 2);
        
        // // Assert a mailable was not sent...
        // Mail::assertNotSent(AnotherMailable::class);

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

    //cuoco 
    public function test_cuoco_toglie_la_pizza(){
        $user = User::factory()->create(['ruolo' => 2]);
        $header = Header::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $response = $this->actingAs($user)->post(route('updateOrder' , $header->id) , [                
            "accettazione" => 2,
        ]);

        $this->assertDatabaseHas('headers' , [
            'id' => $header->id,
        ]);
        
    }

    public function test_cuoco_elimina_ordine(){
        $user = User::factory()->create(['ruolo' => 2]);
        $header = Header::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);      
        $response = $this->actingAs($user)->delete(route('destroyOrder' , $header->id));
        $this->assertDatabaseMissing('headers' , [
            'id' => $header->id,
        ]);
        
    }

    //FATTORINO
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
    }

    public function test_consegna_avvenuta(){
        $user = User::factory()->create(['ruolo' => 3]);
        $header = Header::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->post(route('orderSubmit' , $header->id) , [              
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
        $response = $this->put(route('deliveredOrder' , $header->id) , [              
           
            "accettazione" => 3,          
        ]);
        
        $this->assertDatabaseHas('headers', [
            'id'=> $header->id,
            'accettazione' => 3,
        ]);
    }
    
    
    //test per filtri
    public function test_cerca_prodotto_per_nome(){
        $category = Category::factory()->create();
        $product = Product::factory()->create(['name' => 'Margherita' , 'category_id' => $category->id]);
        $product1 = Product::factory()->create(['name' => 'ciao']);
        $product2= Product::factory()->create(['name' => 'ciao2']);
        $product3 = Product::factory()->create(['name' => 'ciao3']);

        $response = $this->post(route('search' , ['search' => "ciao"]));
        $response->assertSessionHas('searchProduct');
        $response = $this->get(route("pizza" ));
        
    }    

    public function test_cerca_prodotto_per_categoria(){
        $category = Category::factory()->create();
        $category = Category::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $product1 = Product::factory()->create(['category_id' => 2]);
        $product2= Product::factory()->create(['category_id' => 3]);
        $product3 = Product::factory()->create(['category_id' => 3]);

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


    //immagini dei prodotti
    public function test_caricamento_immagini(){
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $image = Image::factory()->create([
            'product_id' => $product->id,
            'img' => $file
        ]);

        $this->assertDatabaseHas('images' , [
            'id' => $image->id,
        ]);
    }
}



