<?php

use App\Models\Header;
use App\Models\SelectedProduct;
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
        Schema::table('headers', function (Blueprint $table) {
            $table->decimal('tot')->after('email')->default(0);
        });
            
        $headers = Header::all();
        foreach($headers as $header){
            $id = $header->id ;
            $prodottiSelezionati = SelectedProduct::all()->where('header_id' , $id);
            $totale = 0;
            foreach($prodottiSelezionati as $prodottoSelezionato){
                $tot = $prodottoSelezionato->quantity * $prodottoSelezionato->price_uni;
                $totale += $tot;
            }
            $header->tot = $totale;
            $header->update(['tot' => $header->tot]);
            
        }
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('headers', function (Blueprint $table) {
            $table->dropColumn('tot');
        });
    }
};
