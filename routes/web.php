<?php

use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RssParserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RssConverterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebRequestLogController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\NewsBlockController;
use App\Models\DataForRssNewsRequest;
use App\Models\NewsBlock;
use Illuminate\Queue\Worker;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/images', [HomeController::class, 'index'])->name('images');


Route::middleware(['guest'])->group(function () {

    Route::get('/register', [UserController::class, 'create'])->name('register.create');
    Route::post('/register', [UserController::class, 'store'])->name('register.store');

    Route::get('/login', [UserController::class, 'loginForm'])->name('login.create');
    Route::post('/login', [UserController::class, 'login'])->name('login');

});

Route::middleware(['user'])->group(function () {

    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/log', [WebRequestLogController::class, 'index'])->name('log');

    Route::post('/start', [WorkerController::class, 'start'])->name('start');
    Route::post('/stop', [WorkerController::class, 'stop'])->name('stop');

    Route::post('/newsblock', [NewsBlockController::class, 'show'])->name('newsblock.show');
    Route::post('/newsblock/update', [NewsBlockController::class, 'update'])->name('newsblock.update');
    Route::post('/newsblock/delete', [NewsBlockController::class, 'delete'])->name('newsblock.delete');

});




Route::fallback(function (){
    abort(404, 'Нет такой страницы');
});


