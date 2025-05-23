<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfesiController;
use App\Http\Controllers\RekapDataController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\GuestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    session()->forget('validated_alumni');
    return view('guest.home');
})->name('guest.home');

// Fallback untuk 404
Route::fallback(function () {
    if (Auth::check()) {
        return response()->view('errors.404', [], 404);
    }
    return response()->view('errors.404_guest', [], 404);
});

// ==========================
// Guest (Alumni)
// ==========================
Route::get('/form-alumni', [GuestController::class, 'create'])->name('form.alumni');
Route::post('/form-alumni', [GuestController::class, 'store'])->name('submit.alumni');
Route::get('/form-alumni/autocomplete-alumni', [GuestController::class, 'getNama'])->name('autocomplete.alumni');
Route::post('/validate-code',  [GuestController::class, 'validateKode'])->name('validate.alumni');

// ==========================
// Atasan
// ==========================
Route::get('/form-atasan', [AlumniController::class, 'create'])->name('form.atasan');
Route::post('/form-atasan', [AlumniController::class, 'store'])->name('submit.atasan');
Route::get('/form-atasan/autocomplete-atasan', [AlumniController::class, 'getNama'])->name('autocomplete.atasan');

// ==========================
// Auth
// ==========================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['web'])->group(function () {
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
});

Route::get('/change-password', [AuthController::class, 'editPassword'])->name('password.form');
Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('password.update');

// ==========================
// Dashboard
// ==========================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/filter', [AdminController::class, 'filter'])->name('dashboard.filter');

    Route::post('/dashboard/lulusan/table', [AdminController::class, 'lulusan_table'])->name('lulusan.table');
    Route::post('/dashboard/masa_tunggu/table', [AdminController::class, 'masa_tunggu_table'])->name('masa_tunggu.table');
    Route::post('/dashboard/performa_lulusan/table', [AdminController::class, 'performa_lulusan_table'])->name('performa_lulusan.table');
});

// ==========================
// Admin (CRUD)
// ==========================
Route::get('/admin/list', [AdminController::class, 'list'])->name('admin.list');
Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
Route::put('/admin/update/{id}', [AdminController::class, 'update'])->name('admin.update');
Route::delete('/admin/destroy/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
Route::get('/tambah-admin', [AdminController::class, 'index_admin'])->name('admin.index');

// ==========================
// Profesi
// ==========================
Route::prefix('profesi')->group(function () {
    Route::get('/', [ProfesiController::class, 'index'])->name('profesi.index');
    Route::get('/list', [ProfesiController::class, 'list'])->name('profesi.list');
    Route::get('/create_ajax', [ProfesiController::class, 'create_ajax'])->name('profesi.create_ajax');
    Route::post('/store_ajax', [ProfesiController::class, 'store_ajax'])->name('profesi.store_ajax');
    Route::get('/{id}/edit_ajax', [ProfesiController::class, 'edit_ajax'])->name('profesi.edit_ajax');
    Route::put('/{id}/update_ajax', [ProfesiController::class, 'update_ajax'])->name('profesi.update_ajax');
    Route::get('/{id}/confirm_ajax', [ProfesiController::class, 'confirm_ajax'])->name('profesi.confirm_ajax');
    Route::delete('/{id}/delete_ajax', [ProfesiController::class, 'delete_ajax'])->name('profesi.delete_ajax');
});

// ==========================
// Laporan / Rekap Data
// ==========================
Route::middleware('auth')->group(function () {
    Route::get('/laporan', [RekapDataController::class, 'index'])->name('laporan');
    Route::get('/laporan/filter', [RekapDataController::class, 'filter'])->name('laporan.filter');
    Route::post('/export-tracer', [RekapDataController::class, 'exportExcel'])->name('laporan.export.tracer');
    Route::post('/export-kepuasan', [RekapDataController::class, 'exportSurveiKepuasan'])->name('laporan.export.kepuasan');
    Route::post('/export-belum-tracer', [RekapDataController::class, 'exportBelumTS'])->name('laporan.export.belumTracer');
    Route::post('/export-belum-survei', [RekapDataController::class, 'exportBelumSurvei'])->name('laporan.export.belumSurvei');
});
