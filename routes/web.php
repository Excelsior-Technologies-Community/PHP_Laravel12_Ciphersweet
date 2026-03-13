<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SecureContactController;

Route::get('/', [SecureContactController::class,'index'])->name('contacts.index');
Route::get('/create',[SecureContactController::class,'create'])->name('contacts.create');
Route::post('/store',[SecureContactController::class,'store'])->name('contacts.store');