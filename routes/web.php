<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ContractController;
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
Route::get('/teams/{id}', [TeamsController::class, 'showGuest'])->name('teams.showGuest');

// visu pakuociu sarasas
Route::get('/deposits', [DepositController::class, 'guest'])->name('deposits.guest');

// qr kodo pateikiamas psl
Route::get('/units/{hash}', [UnitController::class, 'show'])->name('units.show');

// istrinti kai bus baigtos kurimo formos
Route::post('/cr', [UnitController::class, 'encryptAll'])->name('cr');
Route::get('/c', [UnitController::class, 'c'])->name('crypt');
// depozitas pagal id
//

// ---------------------------------------------------------------------------
// NAUDOTOJU POSISTEME
// ---------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    
    // profile edit
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update.picture');
    Route::post('/profile/delete-picture', [ProfileController::class, 'deleteProfilePicture'])->name('profile.delete.picture');

    Route::get('/my-teams', [TeamsController::class, 'index'])->name('teams.index');
    Route::post('/my-teams', [TeamsController::class, 'store'])->name('teams.store');
    Route::get('/my-teams/{id}', [TeamsController::class, 'show'])->name('teams.show');
    Route::post('/my-teams/{id}/accept-invite', [TeamsController::class, 'acceptInvite'])->name('teams.acceptInvite');
    Route::delete('/my-teams/{id}/reject-invite', [TeamsController::class, 'rejectInvite'])->name('teams.rejectInvite');

    // team edit
    Route::get('/my-teams/{id}/edit', [TeamsController::class, 'update'])->name('teams.edit');
    Route::patch('/my-teams/{id}/edit/name', [TeamsController::class, 'updateName'])->name('teams.edit.name');
    Route::post('/my-teams/{id}/edit/picture', [TeamsController::class, 'updatePicture'])->name('teams.edit.picture');
    Route::post('/my-teams/{id}/edit/picture/delete', [TeamsController::class, 'deletePicture'])->name('teams.edit.picture.delete');
    Route::delete('/my-teams/{id}/edit/delete', [TeamsController::class, 'destroy'])->name('teams.edit.delete');
    Route::patch('/my-teams/{id}/edit/address', [TeamsController::class, 'updateAddress'])->name('teams.edit.address');
    Route::patch('/my-teams/{id}/edit/contacts', [TeamsController::class, 'updateContacts'])->name('teams.edit.contacts');
    Route::post('/my-teams/{id}/edit/member', [TeamsController::class, 'updateMember'])->name('teams.edit.member');
    Route::delete('/my-teams/{id}/edit/member/remove/{uid}', [TeamsController::class, 'removeMember'])->name('teams.edit.member.remove');
    Route::post('/my-teams/{id}/edit/member/role/{uid}', [TeamsController::class, 'updateMemberRole'])->name('teams.edit.member.role');
    // user search requests
    Route::get('/users/search', [ProfileController::class, 'search'])->name('users.search');


    Route::get('/my-teams/{id}/deposits', [DepositController::class, 'index'])->name('teams.deposits');
    Route::get('/my-teams/{id}/deposits/search', [DepositController::class, 'fetchDeposits'])->name('teams.deposits.search');
    Route::delete('/my-teams/{id}/deposits/delete/{did}{role}', [DepositController::class, 'destroy'])->name('teams.deposits.delete');
    Route::patch('/my-teams/{id}/deposits/update/{did}{role}', [DepositController::class, 'update'])->name('teams.deposits.update');

    Route::get('/unit/update/{id}', [UnitController::class, 'update'])->name('unit.update');


    Route::get('/my-teams/{id}/contracts', [ContractController::class, 'index'])->name('teams.contracts');
    Route::post('/my-teams/{id}/contracts/store', [ContractController::class, 'store'])->name('teams.contracts-store');
    Route::patch('/my-teams/{id}/contracts/update', [ContractController::class, 'update'])->name('teams.contracts-update');
    Route::delete('/my-teams/{id}/contracts/destroy', [ContractController::class, 'destroy'])->name('teams.contracts-destroy');

    Route::get('/teams-search', [ContractController::class, 'search'])->name('teams.search');

    Route::post('/my-teams/{id}/deposits', [DepositController::class, 'store'])->name('teams.deposits.store');
    Route::post('/my-teams/{id}/deposits2', [DepositController::class, 'store2'])->name('teams.deposits.store2');
    Route::get('/my-teams/{id}/messages', [MessageController::class, 'index'])->name('teams.messages');
    Route::get('/my-teams/{id}/messages/dynamic', [MessageController::class, 'dynamic'])->name('teams.messages-dynamic');
    Route::post('/my-teams/{id}/messages/store', [MessageController::class, 'store'])->name('teams.messages-store');

    //test route
    Route::get('/deposits/{depositId}/units', [DepositController::class, 'getUnits']);


    Route::get('/news/{id}/edit-content', [NewsPostController::class, 'contentEdit'])->name('news.content.edit');
    Route::post('/news/{id}/update-content', [NewsPostController::class, 'updateContent'])->name('news.content.update');
});

require __DIR__.'/auth.php';
