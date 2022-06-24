<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->biginteger('ruolo')->after('mansione')->default(0);
            $users = User::all();
            foreach($users as $user){
                if($user->mansione === "Gestore"){
                    $user->ruolo = 1;
                }elseif($user->mansione === "Cuoco"){
                    $user->ruolo = 2;
                }elseif($user->mansione === "Fattorino"){
                    $user->ruolo = 3;
                }
                $user->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ruolo');
        });
    }
};
