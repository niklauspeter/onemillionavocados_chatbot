<?php
// use App\Http\Controllers\BotManController;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AvocadoController;
use App\Http\Controllers\BotMannController;
// use App\Http\Controllers\BotmanController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
// Route::match(['get', 'post'], '/botman', [AvocadoController::class, 'handle']);
Route::match(['get', 'post'], '/botman', [BotMannController::class, 'handle']);
// Route::match(['get', 'post'], '/botman', [BotmanController::class, 'handle']);

// Route::match(['get', 'post'], '/botman', 'App\Http\Controllers\BotManController@handle');
// Route::match(['get', 'post'], '/botman', 'App\Http\Controllers\AvocadoController@handle');


// Route::get('/chat', function () {
//     $botman = app('botman');

//     $botman->hears('Hi', function($bot) {
//         $bot->startConversation(new App\Conversations\AvocadoConversation());
//     });

//     return $botman;
// });


