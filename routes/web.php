<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\OrderController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/showFixed', [AdminController::class, 'index'])->name('admin.showFixed');
});

Route::middleware(['auth', 'role:supervisor'])->group(function () {
    Route::get('/supervisor/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
    Route::get('/supervisor/showOrderSupervisor', [OrderController::class, 'showOrderSupervisor'])->name('supervisor.showOrderSupervisor');
    Route::get('/supervisor/showAcceptedOrderSupervisor', [OrderController::class, 'showAcceptedOrderSupervisor'])->name('supervisor.showAcceptedOrderSupervisor');
    Route::get('/supervisor/showRejectedOrderSupervisor', [OrderController::class, 'showRejectedOrderSupervisor'])->name('supervisor.showRejectedOrderSupervisor');

    Route::get('/supervisor/showOrderSupervisorLivewire', [OrderController::class, 'showOrderSupervisorLivewire'])->name('supervisor.showOrderSupervisorLivewire');


    Route::post('/supervisor/twoDays', [OrderController::class, 'twoDays'])->name('supervisor.twoDays');
    Route::post('/supervisor/betweenAge', [OrderController::class, 'betweenAge'])->name('supervisor.betweenAge');
    Route::post('/supervisor/adminMoney', [OrderController::class, 'adminMoney'])->name('supervisor.adminMoney');

    Route::post('/supervisor/inActiveThreeDays', [OrderController::class, 'inActiveThreeDays'])->name('supervisor.inActiveThreeDays');
    Route::post('/supervisor/moreSixty', [OrderController::class, 'moreSixty'])->name('supervisor.moreSixty');

    
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/create', [OrderController::class, 'create'])->name('user.create');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/order/showOrder', [OrderController::class, 'index'])->name('order.showOrder');
    Route::DELETE('/order/destroy/{id}', [OrderController::class, 'destroy'])->name('order.destroy');

});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Schedule::Command(RemoveOrder::class)->everySecond();
require __DIR__.'/auth.php';
