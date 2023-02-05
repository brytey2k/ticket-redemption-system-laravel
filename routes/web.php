<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tickets\TicketsController;
use App\Http\Controllers\Users\UsersController;
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

Route::get('/', function () {
    return redirect()
        ->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('tickets')->name('tickets.')->middleware(['auth'])->group(function() {
    Route::get('/generate', [TicketsController::class, 'generate'])->name('generate');
    Route::post('/generate', [TicketsController::class, 'processTicketGeneration'])->name('process-ticket-generation');
});

Route::prefix('tickets')->name('tickets.')->middleware(['auth'])->group(function() {
    Route::get('/redeem', [TicketsController::class, 'redeem'])->name('redeem');
    Route::patch('/redeem', [TicketsController::class, 'processRedemption'])->name('process-redemption')
        ->middleware(['throttle:ticket_redemption']);
    Route::get('/redemption-history', [TicketsController::class, 'history'])->name('redemption-history');
});
Route::resource('tickets', TicketsController::class)->middleware(['auth'])->only(['index']);

Route::resource('users', UsersController::class)->middleware(['auth'])
    ->except(['show']);

require __DIR__.'/auth.php';
