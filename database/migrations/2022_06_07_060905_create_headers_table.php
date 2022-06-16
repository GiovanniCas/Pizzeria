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
        Schema::create('headers', function (Blueprint $table) {
            $table->id();
            $table->biginteger("user_id")->unsigned()->default(1);
            $table->foreign("user_id")->references("id")->on("users");
            $table->string("name")->nullable();
            $table->string("surname")->nullable();
            $table->string("citta")->nullable();
            $table->string("indirizzo")->nullable();
            $table->string("cap")->nullable();
            $table->string("email")->nullable();
            $table->date("data")->nullable();
            $table->time("time")->nullable();
            $table->integer("accettazione")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
        Schema::dropIfExists('headers');
    }
};
