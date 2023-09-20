<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\ExcelExportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/',function(){
//     return view('articles.index');
// });

Route::get('/', [ArticlesController::class, 'index'])->name('articles.index');
Route::get('cantines/archives', [ArticlesController::class, 'archives'])->name('articles.archives');

Route::get('/cantines/chercheliste', [ArticlesController::class, 'chercheMatricule'])->name('cantines.cherche-liste');


Route::get('/cantines/create', [ArticlesController::class, 'create'])->name('articles.create');
Route::get('/cantines/history', [ArticlesController::class, 'history'])->name('articles.history');
Route::get('/cantines/cantine', [ArticlesController::class, 'cantine'])->name('articles.cantine');
Route::post('/cantines/ajout', [ArticlesController::class, 'store'])->name('articles.store');
Route::post('/cantines', [ArticlesController::class, 'storeCantine'])->name('articles.store-cantine');

// Route::get('/cantines/filtre', [ArticlesController::class, 'historyDate'])->name('cantine-date');
Route::get('/cantines/filtrer', [ArticlesController::class, 'historyDate2'])->name('cantine-date-posi');

Route::post('/cantines/choix_terminer', [ArticlesController::class, 'choixMenues'])->name('articles.choix');
Route::post('/cantines/affiche_dates', [ArticlesController::class, 'afficheDate'])->name('cantines.affiche-date');

Route::get('/cantines/{matricule}', [ArticlesController::class, 'choisirMenues'])->name('articles.choisir');
Route::get('/articles/{categorie}/{article}', [ArticlesController::class, 'show'])->name('articles.show');
Route::get('/articles/{article}', [ArticlesController::class, 'edit'])->name('articles.edit');
Route::get('/cantines/{matricule}/{date}', [ArticlesController::class, 'cantineRecherche'])->name('cantines.find');

Route::put('/articles/{choix}', [ArticlesController::class, 'update'])->name('cantines.update-choix');
Route::post('/cantines/afficheChoix', [ArticlesController::class, 'afficheChoix'])->name('cantines.affiche-choix');

Route::get('/cantines-delete/{id}', [ArticlesController::class, 'effacheChoix'])->name('efface.choix');

Route::post('/articles/supprimer', [ArticlesController::class, 'destroy'])->name('articles.destroy');
Route::post('/articles/restaurer', [ArticlesController::class, 'restaurer'])->name('archves.restaurer');

// Route::post('/exportPDF', [ArticlesController::class, 'exportPDF'])->name('history.exportPDF');

Route::post('/export-excel', [ExcelExportController::class, 'export'])->name('export.excel');
Route::post('/import-excel', [ExcelExportController::class, 'processImport'])->name('import.process');