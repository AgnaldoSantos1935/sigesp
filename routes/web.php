<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PessoasController;
use App\Http\Controllers\UnidadeController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/unidades', [UnidadeController::class, 'index'])
        ->name('unidades.index');
        Route::get('/usuarios', function () {
            return view('users.index');
        })->name('users.index');
Route::get('/pessoas/create', [PessoasCrudController::class, 'create'])
    ->name('pessoas.create')
    ->middleware('auth');

 Route::get('/pessoas/data', [PessoasController::class, 'data'])
        ->name('pessoas.data');

    Route::resource('pessoas', PessoasController::class);

    Route::get('/pessoas', [PessoasController::class, 'index'])
        ->name('pessoas.index');


});

require __DIR__.'/auth.php';
