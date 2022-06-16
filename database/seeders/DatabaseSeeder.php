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
            ['name' => 'Pizze'],
            ['name' => 'Bevande'],
            ['name' => 'Dessert'],
        ];

        foreach($categories as $category){
            DB::table("categories")->insert([
                'name'=> $category['name']
            ]);
        }
        
    }
}
