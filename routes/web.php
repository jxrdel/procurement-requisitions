<?php

use App\Http\Controllers\AccountsPayableController;
use App\Http\Controllers\VoteControlRequisitionController;
use App\Http\Controllers\CBRequisitionController;
use App\Http\Controllers\CheckRoomController;
use App\Http\Controllers\ChequeProcessingController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\RequisitionFormController;
use App\Livewire\CostBudgetingRequisition;
use App\Livewire\CreateRequisition;
use App\Livewire\CreateRequisitionRequestForm;
use App\Livewire\LoginForm;
use App\Livewire\RequisitionRequestForm;
use App\Livewire\ViewAccountsPayableRequisition;
use App\Livewire\ViewAccountsPayableVendor;
use App\Livewire\ViewCheckRoomRequisition;
use App\Livewire\ViewCheckStaffVendor;
use App\Livewire\ViewChequeProcessingRequisition;
use App\Livewire\ViewChequeProcessingVendor;
use App\Livewire\ViewVoteControlRequisition;
use App\Livewire\ViewRequisition;
use App\Livewire\ViewRequisitionForm;
use App\Livewire\ViewVoteControlVendor;
use App\Models\VoteControlRequisition;
use Illuminate\Support\Facades\Route;

Route::get('/login', LoginForm::class)->name('login');
Route::get('/logout', [Controller::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [Controller::class, 'index'])->name('/');

    Route::get('/requisitions', [RequisitionController::class, 'index'])->name('requisitions.index');
    Route::get('/requisitions/create/{form}', CreateRequisition::class)->name('requisitions.create');
    Route::get('/requisitions/view/{id}', ViewRequisition::class)->name('requisitions.view');
    Route::get('/getrequisitions', [RequisitionController::class, 'getRequisitions'])->name('getrequisitions');
    Route::get('/getinprogressrequisitions', [RequisitionController::class, 'getInProgressRequisitions'])->name('getinprogressrequisitions');
    Route::get('/getcompletedrequisitions', [RequisitionController::class, 'getCompletedRequisitions'])->name('getcompletedrequisitions');

    Route::get('/requisitions/forms', [RequisitionFormController::class, 'index'])->name('requisition_forms.index');
    Route::get('/requisitions/forms/create', CreateRequisitionRequestForm::class)->name('requisition_forms.create');
    Route::get('/requisitions/forms/view/{id}', ViewRequisitionForm::class)->name('requisition_forms.view');
    Route::get('/requisitions/forms/getforms', [RequisitionFormController::class, 'getForms'])->name('getrequisition_forms');
    Route::get('/requisitions/forms/preview/{id}', [RequisitionFormController::class, 'preview'])->name('requisition_forms.preview');

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
    Route::get('/getcheckstaff_vendors', [CheckRoomController::class, 'getCheckStaffVendors'])->name('getcheckstaff_vendors');
    Route::get('/getcompletedcheckstaff_vendors', [CheckRoomController::class, 'getCompletedCheckStaffVendors'])->name('getcompletedcheckstaff_vendors');
    Route::get('/getinprogresscheckstaff_vendors', [CheckRoomController::class, 'getInProgressCheckStaffVendors'])->name('getinprogresscheckstaff_vendors');

    Route::get('/cheque_processing', [ChequeProcessingController::class, 'index'])->name('cheque_processing.index');
    Route::get('/cheque_processing/view/{id}', ViewChequeProcessingVendor::class)->name('cheque_processing.view')->middleware('can:view-cheque-processing-requisitions');
    Route::get('/getchequeprocessing_vendors', [ChequeProcessingController::class, 'getChequeProcessingVendors'])->name('getchequeprocessing_vendors');
    Route::get('/getcompletedchequeprocessing_vendors', [ChequeProcessingController::class, 'getCompletedChequeProcessingVendors'])->name('getcompletedchequeprocessing_vendors');
    Route::get('/getinprogresschequeprocessing_vendors', [ChequeProcessingController::class, 'getInProgressChequeProcessingVendors'])->name('getinprogresschequeprocessing_vendors');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/getnotifications', [NotificationController::class, 'getNotifications'])->name('notifications.get');
    Route::get('/notifications/view/{id}', [NotificationController::class, 'view'])->name('notifications.view');

    Route::get('/users', [Controller::class, 'users'])->name('users');
    Route::get('/getusers', [Controller::class, 'getUsers'])->name('getusers');

    Route::get('/votes', [Controller::class, 'votes'])->name('votes');
    Route::get('/getvotes', [Controller::class, 'getVotes'])->name('getvotes');

    Route::get('/queue', [Controller::class, 'queue'])->name('queue');
    Route::get('/getqueue', [Controller::class, 'getQueue'])->name('getqueue');

    Route::get('/help', [Controller::class, 'help'])->name('help');
});
use App\Http\Controllers\DepartmentController;

Route::resource('departments', DepartmentController::class)->only([
    'update'
]);
Route::get('departments', [DepartmentController::class, 'getDepartments'])->name('departments.index');
Route::get('/queue/in-progress', [Controller::class, 'getInProgressQueue'])->name('getInProgressQueue');
Route::get('/queue/completed', [Controller::class, 'getCompletedQueue'])->name('getCompletedQueue');
