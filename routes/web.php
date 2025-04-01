<?php

use App\Models\Incident;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncidentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [IncidentController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('incidents/create', [IncidentController::class, 'create'])->name('incidents.create');
    
    

});

Route::middleware('role:Admin')->group(function () {
    Route::resource("users", UserController::class);
    Route::resource("roles", RoleController::class);
});

Route::middleware(['role:Admin|Manager'])->group(function () {
    Route::resource("incidents", IncidentController::class)->except('create');
});   


Route::post('incidents', [IncidentController::class, 'store'])->name('incidents.store');


require __DIR__.'/auth.php';
