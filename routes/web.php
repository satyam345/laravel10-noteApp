<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\welcomeController;
use Illuminate\Support\Facades\Route;

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
Route::get('/', [welcomeController::class, 'welcome'])->name('welcome');
Route::redirect('/dashboard', '/note')->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function() {
  Route::resource('note', NoteController::class);
  Route::post('/note/customCreate', [NoteController::class, 'storeAjax'])->name('note.storeAjax');
  Route::post('/note/customShow', [NoteController::class, 'showAjax'])->name('note.showAjax');
  Route::post('/note/customUpdate', [NoteController::class, 'updateAjax'])->name('note.updateAjax');
  Route::post('/note/customDelete', [NoteController::class, 'destroyAjax'])->name('note.destroyAjax');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
