<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\NewsPostController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// ---------------------------------------------------------------------------
// SVECIO POSISTEME
// ---------------------------------------------------------------------------

// index / visu naujienu sarasas
Route::get('/', [NewsPostController::class, 'index'])->name('news.index');

// naujienos puslapis
Route::get('/news/{id}', [NewsPostController::class, 'show'])->name('news.show');

// visu komandu sarasas
Route::get('/teams', [TeamsController::class, 'guest'])->name('teams.guest');

// komandos display
Route::get('/teams/{id}', [TeamsController::class, 'show'])->name('teams.show');

// visu pakuociu sarasas
Route::get('/deposits', [DepositController::class, 'guest'])->name('deposits.guest');

// qr kodo pateikiamas psl
Route::get('/units/{hash}', [UnitController::class, 'show'])->name('units.show');

// depozitas pagal id
//

// ---------------------------------------------------------------------------
// NAUDOTOJU POSISTEME
// ---------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update.picture');
    Route::post('/profile/delete-picture', [ProfileController::class, 'deleteProfilePicture'])->name('profile.delete.picture');

    Route::get('/my-teams', [TeamsController::class, 'index'])->name('teams.index');
    Route::post('/my-teams', [TeamsController::class, 'store'])->name('teams.store');
    Route::get('/my-teams/{id}', [TeamsController::class, 'show'])->name('teams.show');
    Route::get('/my-teams/{id}/edit', [TeamsController::class, 'show'])->name('teams.edit');
    Route::get('/my-teams/{id}/deposits', [DepositController::class, 'index'])->name('teams.deposits');

    //test route
    Route::get('/deposits/{depositId}/units', [DepositController::class, 'getUnits']);

});

require __DIR__.'/auth.php';
