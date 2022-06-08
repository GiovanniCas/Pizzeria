<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SelectedController;



Route::get('/', [PublicController::class , "welcome"])->name("welcome");
Route::get('/pizze', [PublicController::class , "pizza"])->name("pizza");
Route::get('/carrello', [PublicController::class , "cart"])->name("cart");
Route::get('/form/prodotto', [ProductController::class , "newProduct"])->name("newProduct");


//rotta per inserimento nuovo prodotto
Route::post('/form/prodotto/submit' , [ProductController::class , "submitProduct"])->name("submitProduct");

//rotta per eseguire l'ordine
Route::get('/ordine/form' , [OrderController::class , "orderForm"])->name("orderForm");
Route::post('/ordine/submit' , [OrderController::class , "orderSubmit"])->name("orderSubmit");

//rotta per aggiungere al carrello
Route::post('/aggiungialcarrello' , [SelectedController::class , "addCart"])->name("addCart");