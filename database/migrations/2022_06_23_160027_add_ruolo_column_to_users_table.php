<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
        });
        $users = User::all();
        foreach($users as $user){
            if($user->mansione === "Gestore"){
                $user->ruolo = User::GESTORE;
                //dd('ciao');
            }elseif($user->mansione === "Cuoco"){
                $user->ruolo = USER::CUOCO;
            }elseif($user->mansione === "Fattorino"){
                $user->ruolo = User::FATTORINO;
            }
            $user->update(['ruolo' => $user->ruolo]);
        }
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
