<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\RequisitionController;
use App\Livewire\CreateRequisition;
use App\Livewire\ViewRequisition;
use Illuminate\Support\Facades\Route;

Route::get('/', [Controller::class, 'index'])->name('/');

Route::get('/requisitions', [RequisitionController::class, 'index'])->name('requisitions.index');
Route::get('/requisitions/create', CreateRequisition::class)->name('requisitions.create');
Route::get('/requisitions/view/{id}', ViewRequisition::class)->name('requisitions.view');
Route::get('/getrequisitions', [RequisitionController::class, 'getRequisitions'])->name('getrequisitions');
