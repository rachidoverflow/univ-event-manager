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

    Route::get('/reunions/{reunion}/decisions', [ReunionController::class, 'editDecisions'])->name('reunions.decisions.edit');
    Route::post('/reunions/{reunion}/decisions', [ReunionController::class, 'updateDecisions'])->name('reunions.decisions.update');
    Route::get('/reunions/{reunion}/export-pv', [ReunionController::class, 'exportPV'])->name('reunions.pv.export');

    Route::resource('participants', ParticipantController::class);

    Route::resource('instances', \App\Http\Controllers\InstanceController::class);
    Route::post('/instances/{instance}/members', [\App\Http\Controllers\InstanceController::class, 'addMember'])->name('instances.members.add');
    Route::delete('/instances/{instance}/members/{member}', [\App\Http\Controllers\InstanceController::class, 'removeMember'])->name('instances.members.remove');

    Route::post('reunions/{reunion}/agenda', [AgendaController::class, 'store'])->name('agenda.store');
    Route::delete('agenda/{agenda}', [AgendaController::class, 'destroy'])->name('agenda.destroy');

    Route::post('reunions/{reunion}/invite', [ParticipantController::class, 'invite'])->name('participants.invite');
    Route::post('reunions/{reunion}/status', [ParticipantController::class, 'updateStatus'])->name('participants.status');
    Route::post('reunions/{reunion}/presence/{participant}', [ParticipantController::class, 'markPresence'])->name('participants.presence');
    Route::post('reunions/{reunion}/notify-all', [ParticipantController::class, 'notifyAll'])->name('reunions.notify-all');

    Route::get('reports', [CompteRenduController::class, 'index'])->name('reports.index');
    Route::post('reunions/{reunion}/report', [CompteRenduController::class, 'store'])->name('reports.store');
    Route::get('reports/{compteRendu}/download', [CompteRenduController::class, 'download'])->name('reports.download');
    Route::delete('reports/{compteRendu}', [CompteRenduController::class, 'destroy'])->name('reports.destroy');

    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('api/instances/{instance}/members', [\App\Http\Controllers\InstanceController::class, 'getMembers']);
});
