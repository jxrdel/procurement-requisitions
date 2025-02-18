<?php

use App\Http\Controllers\AccountsPayableController;
use App\Http\Controllers\VoteControlRequisitionController;
use App\Http\Controllers\CBRequisitionController;
use App\Http\Controllers\CheckRoomController;
use App\Http\Controllers\ChequeProcessingController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RequisitionController;
use App\Livewire\CostBudgetingRequisition;
use App\Livewire\CreateRequisition;
use App\Livewire\LoginForm;
use App\Livewire\ViewAccountsPayableRequisition;
use App\Livewire\ViewAccountsPayableVendor;
use App\Livewire\ViewCheckRoomRequisition;
use App\Livewire\ViewCheckStaffVendor;
use App\Livewire\ViewChequeProcessingRequisition;
use App\Livewire\ViewVoteControlRequisition;
use App\Livewire\ViewRequisition;
use App\Livewire\ViewVoteControlVendor;
use App\Models\VoteControlRequisition;
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
    Route::get('/cost_and_budgeting/view/{id}', CostBudgetingRequisition::class)->name('cost_and_budgeting.view')->middleware('can:view-cost-budgeting-requisitions');
    Route::get('/getcostandbudgeting_requisitions', [CBRequisitionController::class, 'getCostAndBudgetingRequisitions'])->name('getcostandbudgeting_requisitions');
    Route::get('/getcompletedcostandbudgeting_requisitions', [CBRequisitionController::class, 'getCompletedCostAndBudgetingRequisitions'])->name('getcompletedcostandbudgeting_requisitions');
    Route::get('/getinprogresscostandbudgeting_requisitions', [CBRequisitionController::class, 'getInProgressCostAndBudgetingRequisitions'])->name('getinprogresscostandbudgeting_requisitions');

    Route::get('/accounts_payable', [AccountsPayableController::class, 'index'])->name('accounts_payable.index');
    Route::get('/accounts_payable/view/{id}', ViewAccountsPayableVendor::class)->name('accounts_payable.view')->middleware('can:view-accounts-payable-requisitions');
    Route::get('/getaccountspayable_vendors', [AccountsPayableController::class, 'getAccountsPayableVendors'])->name('getaccountspayable_vendors');
    Route::get('/getcompletedaccountspayable_vendors', [AccountsPayableController::class, 'getCompletedAccountsPayableVendors'])->name('getcompletedaccountspayable_vendors');
    Route::get('/getinprogressaccountspayable_vendors', [AccountsPayableController::class, 'getInProgressAccountsPayableVendors'])->name('getinprogressaccountspayable_vendors');

    Route::get('/vote_control', [VoteControlRequisitionController::class, 'index'])->name('vote_control.index');
    Route::get('/vote_control/view/{id}', ViewVoteControlVendor::class)->name('vote_control.view')->middleware('can:view-vote-control-requisitions');
    Route::get('/getvotecontrol_vendors', [VoteControlRequisitionController::class, 'getVoteControlVendors'])->name('getvotecontrol_vendors');
    Route::get('/getcompletedvotecontrol_vendors', [VoteControlRequisitionController::class, 'getCompletedVoteControlVendors'])->name('getcompletedvotecontrol_vendors');
    Route::get('/getinprogressvotecontrol_requisitions', [VoteControlRequisitionController::class, 'getInProgressVoteControlVendors'])->name('getinprogressvotecontrol_requisitions');

    Route::get('/check_room', [CheckRoomController::class, 'index'])->name('check_room.index');
    Route::get('/check_room/view/{id}', ViewCheckStaffVendor::class)->name('check_room.view')->middleware('can:view-check-room-requisitions');
    Route::get('/getcheckroom_requisitions', [CheckRoomController::class, 'getCheckStaffVendors'])->name('getcheckroom_requisitions');
    Route::get('/getcompletedcheckroom_requisitions', [CheckRoomController::class, 'getCompletedCheckRoomRequisitions'])->name('getcompletedcheckroom_requisitions');
    Route::get('/getinprogresscheckroom_requisitions', [CheckRoomController::class, 'getInProgressCheckRoomRequisitions'])->name('getinprogresscheckroom_requisitions');

    Route::get('/cheque_processing', [ChequeProcessingController::class, 'index'])->name('cheque_processing.index');
    Route::get('/cheque_processing/view/{id}', ViewChequeProcessingRequisition::class)->name('cheque_processing.view')->middleware('can:view-cheque-processing-requisitions');
    Route::get('/getchequeprocessing_requisitions', [ChequeProcessingController::class, 'getChequeProcessingRequisitions'])->name('getchequeprocessing_requisitions');
    Route::get('/getcompletedchequeprocessing_requisitions', [ChequeProcessingController::class, 'getCompletedChequeProcessingRequisitions'])->name('getcompletedchequeprocessing_requisitions');
    Route::get('/getinprogresschequeprocessing_requisitions', [ChequeProcessingController::class, 'getInProgressChequeProcessingRequisitions'])->name('getinprogresschequeprocessing_requisitions');

    Route::get('/users', [Controller::class, 'users'])->name('users');
    Route::get('/getusers', [Controller::class, 'getUsers'])->name('getusers');

    Route::get('/votes', [Controller::class, 'votes'])->name('votes');
    Route::get('/getvotes', [Controller::class, 'getVotes'])->name('getvotes');

    Route::get('/help', [Controller::class, 'help'])->name('help');
});
