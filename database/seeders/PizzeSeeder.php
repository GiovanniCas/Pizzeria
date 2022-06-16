<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PizzeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pizze= [
            ['name' => "Pizza Margherita",
            'description' => "Pizza farcita con pomodoro e mozzarella.",
            'price' => "6.00",
            'category_id' => "1"],

            ['name' => "Pizza Prosciutto e Funghi",
            'description' => "Pizza farcita con pomodoro ,mozzarella, prosciutto e funghi.",
            'price' => "7.00",
            'category_id' => "1"]
            

        ];

        foreach($pizze as $pizza){
            DB::table("products")->insert([
                'name'=> $pizza['name'],
                'description'=> $pizza['description'],
                'price'=> $pizza['price'],
                'category_id'=> $pizza['category_id'],
            ]);
        }
    }
}
