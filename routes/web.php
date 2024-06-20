<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/', [App\Http\Controllers\PublishedController::class, 'index']);
Route::get('/international', [App\Http\Controllers\PublishedController::class, 'international']);

Route::prefix('published')->group(function () {

  Route::post('/list', [App\Http\Controllers\PublishedController::class, 'list']);
  Route::post('/chart', [App\Http\Controllers\PublishedController::class, 'getChart']);
  Route::post('/list/international', [App\Http\Controllers\PublishedController::class, 'listInternational']);
  Route::post('/chart/international', [App\Http\Controllers\PublishedController::class, 'getChartInternational']);

});



Route::prefix('citation')->group(function () {
  Route::get('/', [App\Http\Controllers\CitationController::class, 'index']);
  Route::post('/list', [App\Http\Controllers\CitationController::class, 'list']);
  Route::post('/chart', [App\Http\Controllers\CitationController::class, 'getChart']);
  Route::post('/list/international', [App\Http\Controllers\CitationController::class, 'listInternational']);
  Route::post('/chart/international', [App\Http\Controllers\CitationController::class, 'getChartInternational']);

});

Route::get('citation-international', [App\Http\Controllers\CitationController::class, 'international']);

Route::prefix('intellectual')->group(function () {

  Route::get('/', [App\Http\Controllers\IntellectualController::class, 'index']);
  Route::post('/list', [App\Http\Controllers\IntellectualController::class, 'list']);
  Route::post('/chart', [App\Http\Controllers\IntellectualController::class, 'getChart']);

});

Route::prefix('research-budget')->group(function () {

  Route::get('/', [App\Http\Controllers\ResearchBudgetController::class, 'index']);
  Route::post('/list', [App\Http\Controllers\ResearchBudgetController::class, 'list']);
  Route::post('/chart', [App\Http\Controllers\ResearchBudgetController::class, 'getChart']);

});

Route::prefix('service-budget')->group(function () {

  Route::get('/', [App\Http\Controllers\ServiceBudgetController::class, 'index']);
  Route::post('/list', [App\Http\Controllers\ServiceBudgetController::class, 'list']);
  Route::post('/chart', [App\Http\Controllers\ServiceBudgetController::class, 'getChart']);

});

Route::prefix('publishing-support')->group(function () {

  Route::get('/', [App\Http\Controllers\PublishingSupportController::class, 'index']);
  Route::post('/list', [App\Http\Controllers\PublishingSupportController::class, 'list']);
  Route::post('/chart', [App\Http\Controllers\PublishingSupportController::class, 'getChart']);

});

Route::prefix('presenting')->group(function () {

  Route::get('/', [App\Http\Controllers\PresentingController::class, 'index']);
  Route::post('/list', [App\Http\Controllers\PresentingController::class, 'list']);
  Route::post('/chart', [App\Http\Controllers\PresentingController::class, 'getChart']);

});
