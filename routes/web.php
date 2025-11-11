<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/auth/login', [LoginController::class, 'index'])->name('login');
Route::post('/auth/check', [LoginController::class, 'login'])->name('check');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    // Dashboard & Home
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/', function () {
        return redirect()->route('home');
    });
});


Route::middleware('auth')->group(function () {
    // TUGAS (Task)
    Route::get('tasks/data', [TaskController::class, 'index'])->name('tasks.index');
    Route::put('tasks/sync', [TaskController::class, 'sync'])->name('tasks.sync');
    Route::patch('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('tasks/reorder', [TaskController::class, 'updateOrder'])->name('tasks.reorder');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::get('/employees/assign', [EmployeeController::class, 'showAssignmentForm'])->name('employees.work');

    Route::resource('divisions', DivisionController::class)->except(['create', 'show', 'edit']);
    Route::resource('roles', RoleController::class)->except(['create', 'show', 'edit']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
});

require __DIR__ . '/auth.php';
