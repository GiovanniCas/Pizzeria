<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users= [
            ['name' => "Giovanni",
            'surname' => "Castagna",
            'email' => "giovanni@giovanni.it",
            'password' => Hash::make('password'),
            'mansione' => 'Gestore'],

            ['name' => "Marco",
            'surname' => "Rossi",
            'email' => "marco@marco.it",
            'password' => Hash::make('password'),
            'mansione' => 'Cuoco'],

            ['name' => "Giuseppe",
            'surname' => "Verdi",
            'email' => "giuseppe@giuseppe.it",
            'password' => Hash::make('password'),
            'mansione' => 'Fattorino'],
        ];

        foreach($users as $user){
            DB::table("users")->insert([
                'name'=> $user['name'],
                'surname'=> $user['surname'],
                'email'=> $user['email'],
                'password'=> $user['password'],
                'mansione'=> $user['mansione'],
            ]);
        }
    }
}
