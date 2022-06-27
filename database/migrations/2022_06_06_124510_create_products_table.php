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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->biginteger("category_id")->unsigned();
            $table->foreign("category_id")->references("id")->on("categories")->onDelete('cascade');
            $table->string("name");
            $table->text("description");
            $table->decimal("price");
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
        $table->dropForeign(["category_id"]);
        
        Schema::dropIfExists('products');
    }
};
