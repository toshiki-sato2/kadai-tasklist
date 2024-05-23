<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*無名関数としてfunctionの定義*/
/*/にGET命令が来たら、それはwelcome.blade.phpに飛ばせよ*/
Route::get('/', function () {
    return view('welcome');
});
