<?php

use App\Http\Controllers\Test;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [Test::class, 'index']);
