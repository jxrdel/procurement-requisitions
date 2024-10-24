<?php

use App\Http\Controllers\AccountsRequisitionController;
use App\Http\Controllers\CBRequisitionController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RequisitionController;
use App\Livewire\CostBudgetingRequisition;
use App\Livewire\CreateRequisition;
use App\Livewire\LoginForm;
use App\Livewire\ViewAccountsRequisition;
use App\Livewire\ViewRequisition;
use App\Models\AccountsRequisition;
use Illuminate\Support\Facades\Route;

Route::get('/login', LoginForm::class)->name('login');
Route::get('/logout', [Controller::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [Controller::class, 'index'])->name('/');

    Route::get('/requisitions', [RequisitionController::class, 'index'])->name('requisitions.index');
    Route::get('/requisitions/create', CreateRequisition::class)->name('requisitions.create');
    Route::get('/requisitions/view/{id}', ViewRequisition::class)->name('requisitions.view');
    Route::get('/getrequisitions', [RequisitionController::class, 'getRequisitions'])->name('getrequisitions');
    Route::get('/getinprogressrequisitions', [RequisitionController::class, 'getInProgressRequisitions'])->name('getinprogressrequisitions');
    Route::get('/getcompletedrequisitions', [RequisitionController::class, 'getCompletedRequisitions'])->name('getcompletedrequisitions');

    Route::get('/cost_and_budgeting', [CBRequisitionController::class, 'index'])->name('cost_and_budgeting.index');
    Route::get('/cost_and_budgeting/view/{id}', CostBudgetingRequisition::class)->name('cost_and_budgeting.view');
    Route::get('/getcostandbudgeting_requisitions', [CBRequisitionController::class, 'getCostAndBudgetingRequisitions'])->name('getcostandbudgeting_requisitions');
    Route::get('/getcompletedcostandbudgeting_requisitions', [CBRequisitionController::class, 'getCompletedCostAndBudgetingRequisitions'])->name('getcompletedcostandbudgeting_requisitions');
    Route::get('/getinprogresscostandbudgeting_requisitions', [CBRequisitionController::class, 'getInProgressCostAndBudgetingRequisitions'])->name('getinprogresscostandbudgeting_requisitions');

    Route::get('/accounts_requisitions', [AccountsRequisitionController::class, 'index'])->name('accounts_requisitions.index');
    Route::get('/accounts_requisitions/view/{id}', ViewAccountsRequisition::class)->name('accounts_requisitions.view');
    Route::get('/getaccounts_requisitions', [AccountsRequisitionController::class, 'getAccountsRequisitions'])->name('getaccounts_requisitions');
    Route::get('/getcompletedaccounts_requisitions', [AccountsRequisitionController::class, 'getCompletedAccountsRequisitions'])->name('getcompletedaccounts_requisitions');
    Route::get('/getinprogressaccounts_requisitions', [AccountsRequisitionController::class, 'getInProgressAccountsRequisitions'])->name('getinprogressaccounts_requisitions');
});
