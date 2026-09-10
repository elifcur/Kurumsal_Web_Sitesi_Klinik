<?php

use Illuminate\Support\Facades\Route;
use Templates\Clinic\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use Templates\Clinic\Http\Controllers\Public\AppointmentController;

Route::get('/randevu', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('/randevu', [AppointmentController::class, 'store'])->middleware('throttle:contact')->name('appointments.store');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin', 'can:manage-sector'])->group(function (): void {
    Route::resource('appointments', AdminAppointmentController::class)->only(['index', 'show', 'update', 'destroy']);
});
