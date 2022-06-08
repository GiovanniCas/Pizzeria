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
        Schema::create('selected_products', function (Blueprint $table) {
            $table->id();
            $table->biginteger("product_id")->unsigned();
            $table->foreign("product_id")->references("id")->on("products");
            $table->biginteger("header_id")->unsigned();
            $table->foreign("header_id")->references("id")->on("headers");
            $table->integer("quantity");
            $table->integer("price_tot");
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
        $table->dropForeign(["product_id"]);
        $table->dropForeign(["header_id"]);
        Schema::dropIfExists('selected_products');
    }
};
