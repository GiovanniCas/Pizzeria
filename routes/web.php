<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Frontend\PublicController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\RevisorController;
use App\Http\Controllers\SelectedController;


// Parte fontend

Route::get('/', [PublicController::class , "welcome"])->name("welcome");
Route::get('/prodotti', [ProductController::class , "pizza"])->name("pizza");
Route::get('/carrello', [PublicController::class , "cart"])->name("cart");

//vista per categorie
Route::get('/categoria/{category}', [PublicController::class , "categoryView"])->name("categoryView");

//rotta per aggiungere al carrello
Route::post('/aggiungialcarrello' , [PublicController::class , "addCart"])->name("addCart");

//modifica elementi dal carrello
Route::get('/update/ordine/{prodottoSelezionato}' , [PublicController::class , "updateQuantity"])->name("updateQuantity");
Route::put('/modifica/ordine/{prodottoSelezionato}' , [PublicController::class , "modificaQuantita"])->name("modificaQuantita");

//rotta per eseguire l'ordine
Route::get('/ordine/form' , [PublicController::class , "orderForm"])->name("orderForm");
Route::post('/ordine/submit' , [PublicController::class , "orderSubmit"])->name("orderSubmit");



//ADMIN

Route::prefix('/admin')->group(function () {
    //aggiunta nuovo prodotto
    Route::get('/form/prodotto', [ProductController::class , "newProduct"])->name("newProduct");
    Route::post('/form/prodotto/submit' , [ProductController::class , "submitProduct"])->name("submitProduct");

    //rotta immagini
    Route::get('/immagini', [ProductController::class, 'getImages'])->name('getImages');
    Route::post('/images/upload' , [ProductController::class , "uploadImages"])->name('uploadImages');
    Route::delete('/images/remove', [ProductController::class, 'removeImages'])->name('removeImages');

    //rotte per modifica ed eliminazione prodotti
    Route::get('/form/modifica/{product}' , [ProductController::class , "formModify"])->name("formModify");
    Route::put('/form/modifica/submit/{product}' , [ProductController::class , "modifyProduct"])->name("modifyProduct");
    Route::delete('/elimina/prodotto/{product}' , [ProductController::class , "deleteProduct"])->name("deleteProduct");

    //ricerca prodotto
    Route::post('/search' , [ProductController::class , "search"])->name('search');
    Route::post('/cerca/utente' , [RevisorController::class , "searchUser"])->name('searchUser');
    Route::post('/cerca/ordine' , [RevisorController::class , "searchOrder"])->name('searchOrder');

    //rotte per categorie 
    Route::get('/aggiungi/categoria' , [ProductController::class , "addCategory"])->name('addCategory');
    Route::post('/aggiungi/nuova/categoria' , [ProductController::class , "addNewCategory"])->name('addNewCategory');
    Route::get('/modifica/{category}' , [ProductController::class , "modifyCategory"])->name('modifyCategory');
    Route::put('/modifica/categoria/{category}' , [ProductController::class , "modifyNewCategory"])->name('modifyNewCategory');
    Route::delete('/elimina/categoria/{category}' , [ProductController::class , "deleteCategory"])->name('deleteCategory');

    //rotta pagina revisore
    // Route::get('/revisore' , [RevisorController::class , "revisor"])->name("revisor");
    
    //cuoco
    Route::put('/conferma/ordine/{header}' , [RevisorController::class , "updateOrder"])->name("updateOrder");

    //eliminazione ordini da parte del revisore
    Route::delete('/elimina/ordine/{header}' , [RevisorController::class , "destroyOrder"])->name("destroyOrder");

    //rotta per lista ordini
    Route::get('/ordini' , [RevisorController::class , "orderList"])->name('orderList');

    //creazione utenti
    Route::get('/utenti' , [RevisorController::class , "utenti"])->name('utenti');
    Route::get('/personale' , [RevisorController::class , "staff"])->name('staff');
    Route::post('/aggiungi/personale' , [RevisorController::class , "addStaff"])->name('addStaff');
    // Route::get('/aggiorna' , [RevisorController::class , "aggiorna"])->name('aggiorna');

    //modifica ed eliminazione utente
    Route::get('/modifica/user/{user}' , [RevisorController::class , "updateUtente"])->name('updateUtente');
    Route::put('/modifica/utente/{user}' , [RevisorController::class , "updateUser"])->name('updateUser');
    Route::delete('/elimina/utente/{user}' , [RevisorController::class , "deleteUser"])->name('deleteUser');

    //rotta pagina consegne/fattorino
    Route::get('/fattorino' , [RevisorController::class , "fattorino"])->name('fattorino');
    //Route::get('/consegne' , [RevisorController::class , "consegne"])->name('consegne');
    Route::put('/accettazione/consegne/{header}' , [RevisorController::class , "acceptOrder"])->name('acceptOrder');
    Route::put('/ordine/consegnato/{header}' , [RevisorController::class , "deliveredOrder"])->name('deliveredOrder');
});



