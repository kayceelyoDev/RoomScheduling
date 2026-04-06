<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserDashboard;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\RoomsController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRequestController;

Route::get('/', function () {
    return view('welcome');
});



//user profile creations end points//

Route::middleware('auth')->group(function () {
   Route::resource('user-profile', UserProfileController::class); 
});

//admin dashboard end point//
Route::middleware(['auth', 'verified', 'has.profile','is.admin'])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('manageSection', SectionController::class);
    
    Route::resource('manageClassrooms', RoomsController::class);
    Route::resource('manageSchedule', ScheduleController::class);

    //manage users endpoints// 

    Route::get('/manageusers', [UserController::class, 'index'])->name('manageUsers.index');
    Route::get('/manageusers/create', [UserController::class, 'create'])->name('manageUsers.create');
    Route::post('/manageusers', [UserController::class, 'store'])->name('manageUsers.store');
    Route::get('/manageusers/{user}/edit', [UserController::class, 'edit'])->name('manageUsers.edit');
    Route::put('/manageusers/{user}', [UserController::class, 'update'])->name('manageUsers.update');
    Route::delete('/manageusers/{user}', [UserController::class, 'destroy'])->name('manageUsers.destroy');

    Route::get('/admin/room-requests', [UserRequestController::class, 'adminIndex'])->name('userRequest.admin.index');
    Route::patch('/admin/room-requests/{userRequest}/approve', [UserRequestController::class, 'approve'])->name('userRequest.approve');
    Route::patch('/admin/room-requests/{userRequest}/reject', [UserRequestController::class, 'reject'])->name('userRequest.reject');
});


//routes for user dashboard //

Route::middleware(['auth', 'verified', 'has.profile'])->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'verified', 'has.profile', 'is.teacher'])->group(function () {
    Route::get('/room-requests', [UserRequestController::class, 'index'])->name('userRequest.index');
    Route::post('/room-requests', [UserRequestController::class, 'store'])->name('userRequest.store');
    Route::delete('/room-requests/{userRequest}', [UserRequestController::class, 'destroy'])->name('userRequest.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
