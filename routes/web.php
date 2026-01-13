<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PessoasController;
use App\Http\Controllers\PessoasJuridicasController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\UnidadeEscolarController;
use App\Http\Controllers\LicitacaoController;
use App\Http\Controllers\InstrumentoJuridicoController;
use App\Http\Controllers\TermoAditivoController;
use App\Http\Controllers\ApostilamentoController;
use App\Http\Controllers\DesignacaoController;
use App\Http\Controllers\InstrumentoJuridicoItemController;
use App\Http\Controllers\EmpenhoController;
use App\Http\Controllers\AvariaController;
use App\Http\Controllers\DemandaController;


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

    Route::get('/unidades', [UnidadeEscolarController::class, 'index'])
        ->name('unidades.index');
    Route::get('/unidades/data', [UnidadeEscolarController::class, 'data'])
        ->name('unidades.data');
    Route::resource('unidades', UnidadeEscolarController::class);

        Route::get('/usuarios', function () {
            return view('users.index');
        })->name('users.index');
    // Rotas de Pessoas Físicas (CRUD completo, com DataTables)
    Route::get('/pessoas/data', [PessoasController::class, 'data'])->name('pessoas.data');
    Route::resource('pessoas', PessoasController::class);

Route::resource('pessoas_juridicas', PessoasJuridicasController::class);
    Route::get('/pessoas_juridicas', [PessoasJuridicasController::class, 'index'])
        ->name('pessoas_juridicas.index');
   Route::get('/pessoas_juridicas/create', [PessoasJuridicasController::class, 'create'])
        ->name('pessoas_juridicas.create');
    Route::get('/pessoas_juridicas/data', [PessoasJuridicasController::class, 'data'])
        ->name('pessoas_juridicas.data');


    // Licitações
    Route::get('/licitacoes/data', [LicitacaoController::class, 'data'])->name('licitacoes.data');
    Route::resource('licitacoes', LicitacaoController::class)->only(['index','create','store']);

    // Instrumentos Jurídicos (Genérico)
    Route::get('/instrumentos/data', [InstrumentoJuridicoController::class, 'data'])->name('instrumentos.data');
    Route::resource('instrumentos', InstrumentoJuridicoController::class)->only(['index','create','store']);

    // Contratos (Alias para Instrumentos)
    Route::get('/contratos', [InstrumentoJuridicoController::class, 'index'])->defaults('tipo', 'contrato')->name('contratos.index');
    Route::get('/contratos/data', [InstrumentoJuridicoController::class, 'data'])->defaults('tipo', 'contrato')->name('contratos.data');
    Route::get('/contratos/create', [InstrumentoJuridicoController::class, 'create'])->defaults('tipo', 'contrato')->name('contratos.create');
    Route::post('/contratos', [InstrumentoJuridicoController::class, 'store'])->defaults('tipo', 'contrato')->name('contratos.store');

    // Convênios (Alias para Instrumentos)
    Route::get('/convenios', [InstrumentoJuridicoController::class, 'index'])->defaults('tipo', 'convenio')->name('convenios.index');
    Route::get('/convenios/data', [InstrumentoJuridicoController::class, 'data'])->defaults('tipo', 'convenio')->name('convenios.data');
    Route::get('/convenios/create', [InstrumentoJuridicoController::class, 'create'])->defaults('tipo', 'convenio')->name('convenios.create');
    Route::post('/convenios', [InstrumentoJuridicoController::class, 'store'])->defaults('tipo', 'convenio')->name('convenios.store');

    // Termos Aditivos
    Route::get('/termos-aditivos/data', [TermoAditivoController::class, 'data'])->name('termos_aditivos.data');
    Route::resource('termos_aditivos', TermoAditivoController::class)->only(['index','create','store']);

    // Apostilamentos
    Route::get('/apostilamentos/data', [ApostilamentoController::class, 'data'])->name('apostilamentos.data');
    Route::resource('apostilamentos', ApostilamentoController::class)->only(['index','create','store']);

    // Designações
    Route::get('/designacoes/data', [DesignacaoController::class, 'data'])->name('designacoes.data');
    Route::resource('designacoes', DesignacaoController::class)->only(['index','create','store']);

    // Itens de Instrumentos Jurídicos
    Route::get('instrumentos/{instrumento}/items', [InstrumentoJuridicoItemController::class, 'index'])->name('instrumentos.items.index');
    Route::get('instrumentos/{instrumento}/items/create', [InstrumentoJuridicoItemController::class, 'create'])->name('instrumentos.items.create');
    Route::post('instrumentos/{instrumento}/items', [InstrumentoJuridicoItemController::class, 'store'])->name('instrumentos.items.store');
    Route::get('instrumentos/{instrumento}/items/{item}/edit', [InstrumentoJuridicoItemController::class, 'edit'])->name('instrumentos.items.edit');
    Route::put('instrumentos/{instrumento}/items/{item}', [InstrumentoJuridicoItemController::class, 'update'])->name('instrumentos.items.update');
    Route::delete('instrumentos/{instrumento}/items/{item}', [InstrumentoJuridicoItemController::class, 'destroy'])->name('instrumentos.items.destroy');

    // Módulo Financeiro
    Route::resource('empenhos', EmpenhoController::class);

    // Módulo NT (Notas Técnicas)
    Route::resource('avarias', AvariaController::class);

    // Módulo Fábrica de Software
    Route::resource('demandas', DemandaController::class);

});

require __DIR__.'/auth.php';
