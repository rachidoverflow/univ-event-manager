<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReunionController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\CompteRenduController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('reunions', ReunionController::class);

    Route::post('reunions/{reunion}/agenda', [AgendaController::class, 'store'])->name('agenda.store');
    Route::delete('agenda/{agenda}', [AgendaController::class, 'destroy'])->name('agenda.destroy');

    Route::post('reunions/{reunion}/invite', [ParticipantController::class, 'invite'])->name('participants.invite');
    Route::post('reunions/{reunion}/status', [ParticipantController::class, 'updateStatus'])->name('participants.status');
    Route::post('reunions/{reunion}/presence/{user}', [ParticipantController::class, 'markPresence'])->name('participants.presence');

    Route::post('reunions/{reunion}/report', [CompteRenduController::class, 'store'])->name('reports.store');
    Route::get('reports/{compteRendu}/download', [CompteRenduController::class, 'download'])->name('reports.download');
});
