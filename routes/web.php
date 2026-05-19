<?php

use App\Http\Controllers\SecureContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Contact Management Routes
Route::get('/', [SecureContactController::class, 'index'])->name('contacts.index');
Route::get('/create', [SecureContactController::class, 'create'])->name('contacts.create');
Route::post('/store', [SecureContactController::class, 'store'])->name('contacts.store');
Route::get('/edit/{id}', [SecureContactController::class, 'edit'])->name('contacts.edit');
Route::put('/update/{id}', [SecureContactController::class, 'update'])->name('contacts.update');
Route::delete('/delete/{id}', [SecureContactController::class, 'destroy'])->name('contacts.delete');

// Optional export route
Route::get('/export', [SecureContactController::class, 'export'])->name('contacts.export');

// Note: Auth routes removed - add them manually if needed later