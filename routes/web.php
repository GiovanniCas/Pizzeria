<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RevisorController;
use App\Http\Controllers\SelectedController;



Route::get('/', [PublicController::class , "welcome"])->name("welcome");
Route::get('/pizze', [PublicController::class , "pizza"])->name("pizza");
Route::get('/carrello', [PublicController::class , "cart"])->name("cart");
Route::get('/form/prodotto', [ProductController::class , "newProduct"])->name("newProduct");

//ricerca prodotto
Route::post('/search' , [ProductController::class , "search"])->name('search');


//rotta per inserimento nuovo prodotto
Route::post('/form/prodotto/submit' , [ProductController::class , "submitProduct"])->name("submitProduct");

//rotte per modifica ed eliminazione prodotti
Route::get('/form/modifica/{product}' , [ProductController::class , "formModify"])->name("formModify");
Route::put('/form/modifica/submit/{product}' , [ProductController::class , "modifyProduct"])->name("modifyProduct");
Route::delete('/elimina/prodotto/{product}' , [ProductController::class , "deleteProduct"])->name("deleteProduct");

//rotta per aggiungere al carrello
Route::post('/aggiungialcarrello' , [SelectedController::class , "addCart"])->name("addCart");

//rotta per eseguire l'ordine
Route::get('/ordine/form' , [OrderController::class , "orderForm"])->name("orderForm");
Route::post('/ordine/submit' , [OrderController::class , "orderSubmit"])->name("orderSubmit");

//rotta pagina revisore
Route::get('/revisore' , [RevisorController::class , "revisor"])->name("revisor");
Route::put('/conferma/ordine/{header}' , [RevisorController::class , "updateOrder"])->name("updateOrder");


//modifica elementi dal carrello
Route::get('/update/ordine/{prodottoSelezionato}' , [SelectedController::class , "updateQuantity"])->name("updateQuantity");
Route::put('/modifica/ordine/{prodottoSelezionato}' , [SelectedController::class , "modificaQuantita"])->name("modificaQuantita");


//eliminazione ordini da parte del revisore
Route::delete('/elimina/prodotto/{header}' , [OrderController::class , "destroyOrder"])->name("destroyOrder");

//creazione personale
Route::get('/utenti' , [RevisorController::class , "utenti"])->name('utenti');
Route::get('/personale' , [RevisorController::class , "staff"])->name('staff');
Route::post('/aggiungi/personale' , [RevisorController::class , "addStaff"])->name('addStaff');


//rotta pagina consegne/fattorino
Route::get('/fattorino' , [RevisorController::class , "fattorino"])->name('fattorino');
Route::get('/consegne' , [RevisorController::class , "consegne"])->name('consegne');
Route::put('/accettazione/consegne/{header}' , [RevisorController::class , "acceptOrder"])->name('acceptOrder');
Route::put('/ordine/consegnato/{header}' , [RevisorController::class , "deliveredOrder"])->name('deliveredOrder');
