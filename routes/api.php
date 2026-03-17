<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SSHController;

Route::post('/wp/store', [SSHController::class, 'store']);