<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRelationController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('user-relations', UserRelationController::class);
