<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/wp', [App\Http\Controllers\SSHController::class, 'index']);
Route::get('wp/ssh', [App\Http\Controllers\SSHController::class, 'checkSSH']);
Route::post('wp/store', [App\Http\Controllers\SSHController::class, 'store']);


