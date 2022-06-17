<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $categories= [
            [
            'name' => 'Pizze',
            'description' => 'Prova le pizze migliori al mondo'
            ],
           
            [
            'name' => 'Bevande',
            'description' => 'Tutti i tipi di bevanda'
            ],
            [
            'name' => 'Dessert',
            'description' => 'Deseert di tutti i tipi'
            ],
        ];

        foreach($categories as $category){
            DB::table("categories")->insert([
                'name'=> $category['name'],
                'description'=> $category['description']
            ]);
        }
        
    }
}
