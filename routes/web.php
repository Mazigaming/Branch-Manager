<?php

use App\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BranchController::class, 'index']);

Route::prefix('branches')->group(function () {
    Route::get('/', [BranchController::class, 'index'])->name('branches.index');
    Route::post('/', [BranchController::class, 'store'])->name('branches.store');
    Route::put('/{id}', [BranchController::class, 'update'])->name('branches.update');
    Route::delete('/{id}', [BranchController::class, 'destroy'])->name('branches.destroy');
    Route::post('/{id}/add-leaf', [BranchController::class, 'addLeaf'])->name('branches.addLeaf');
});

Route::prefix('leaves')->group(function () {
    Route::delete('/{id}', [BranchController::class, 'deleteLeaf'])->name('leaves.destroy');
    Route::post('/{id}/move', [BranchController::class, 'moveLeaf'])->name('leaves.move');
});
