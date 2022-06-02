<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [IndexController::class, 'index']);
Route::get('/dump-db', [IndexController::class, 'dumpDatabase']);
Route::resource('columns', ColumnController::class)
	->names(['index' => 'columns'])
	->only(['index', 'store', 'update', 'destroy']);

Route::resource('cards', CardController::class)
	->names(['index' => 'cards'])
	->only(['store', 'update', 'destroy']);
