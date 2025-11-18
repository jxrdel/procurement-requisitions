<div>
    @include('add-log')
    @include('livewire.vendor-invoices-modal')
    <div class="card" x-data="{
        sent_to_cb: $wire.entangle('sent_to_cb'),
        ps_approval: $wire.entangle('ps_approval'),
        isEditingProcurement1: $wire.entangle('isEditingProcurement1'),
        isEditingProcurement2: $wire.entangle('isEditingProcurement2')
    }" x-cloak>
        <div class="card-body">

            <div class="d-flex align-items-center justify-content-between mb-5 position-relative">
                {{-- Back button --}}
                @can('view-requisitions-index')
                    <a href="{{ $backUrl }}" class="btn btn-dark">
                        <i class="ri-arrow-left-circle-line me-1"></i> Back
                    </a>
                @endcan

                {{-- Centered requisition number --}}
                <div class="position-absolute start-50 translate-middle-x text-center">
                    <h1 class="h3 mb-0 text-gray-800">
                        <strong>{{ $this->requisition_no }}</strong>
                        @if ($this->requisition->is_completed)
                            <span class="badge rounded-pill bg-success fs-5"
                                style="background-color: #8bc34a !important;">
                                Completed
                            </span>
                        @endif
                    </h1>
                </div>
                @if ($this->requisition->requisitionForm)
                    {{-- View Requisition Form button --}}
                    <a href="{{ route('requisition_forms.view', ['id' => $this->requisition->requisitionForm->id]) }}"
                        target="_blank" class="btn btn-success">
                        <i class="fa-solid fa-eye me-1"></i> View Requisition Form
                    </a>
                @endif
            </div>


            <div class="nav-align-top mb-6" style="min-height: 380px">
                <ul wire:ignore class="nav nav-tabs mb-4 nav-fill" role="tablist">
                    <li x-on:click="$wire.active_pane = 'procurement1'" wire:ignore class="nav-item mb-1 mb-sm-0">
                        <button type="button" @class([
                            'nav-link',
                            'active' => $this->active_pane === 'procurement1',
                        ]) role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-justified-procurement1" aria-controls="navs-justified-procurement1"
                            aria-selected="true">
                            <i class="bi bi-1-circle-fill me-1_5"></i> Procurement
                        </button>
                    </li>
                    <li x-on:click="$wire.active_pane = 'cost_and_budgeting'" wire:ignore class="nav-item mb-1 mb-sm-0">
                        <button type="button" @class([
                            'nav-link',
                            'active' => $this->active_pane === 'cost_and_budgeting',
                        ]) role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-justified-cost_budgeting"
                            aria-controls="navs-justified-cost_budgeting" aria-selected="false">
                            <i class="bi bi-2-circle-fill me-1_5"></i> Cost & Budgeting
                        </button>
                    </li>
                    <li x-on:click="$wire.active_pane = 'procurement2'" wire:ignore class="nav-item mb-1 mb-sm-0">
                        <button type="button" @class([
                            'nav-link',
                            'active' => $this->active_pane === 'procurement2',
                        ]) role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-justified-procurement2" aria-controls="navs-justified-procurement2"
                            aria-selected="false">
                            <i class="bi bi-3-circle-fill me-1_5"></i> Procurement
                        </button>
                    </li>
                    <li x-on:click="$wire.active_pane = 'accounts_payable'" wire:ignore class="nav-item mb-1 mb-sm-0">
                        <button type="button" @class([
                            'nav-link',
                            'active' => $this->active_pane === 'accounts_payable',
                        ]) role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-justified-accounts_payable"
                            aria-controls="navs-justified-accounts_payable" aria-selected="false">
                            <i class="bi bi-4-circle-fill me-1_5"></i> AP
                        </button>
                    </li>
                    <li x-on:click="$wire.active_pane = 'votecontrol'" wire:ignore class="nav-item mb-1 mb-sm-0">
                        <button type="button" @class(['nav-link', 'active' => $this->active_pane === 'votecontrol']) role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-justified-votecontrol" aria-controls="navs-justified-votecontrol"
                            aria-selected="false">
                            <i class="bi bi-5-circle-fill me-1_5"></i> Vote Control
                        </button>
                    </li>
                    <li x-on:click="$wire.active_pane = 'checkroom'" wire:ignore class="nav-item mb-1 mb-sm-0">
                        <button type="button" @class(['nav-link', 'active' => $this->active_pane === 'checkroom']) role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-justified-check-room" aria-controls="navs-justified-check-room"
                            aria-selected="false">
                            <i class="bi bi-6-circle-fill me-1_5"></i> Check Staff
                        </button>
                    </li>
                    <li x-on:click="$wire.active_pane = 'chequeprocessing'" wire:ignore class="nav-item mb-1 mb-sm-0">
                        <button type="button" @class([
                            'nav-link',
                            'active' => $this->active_pane === 'chequeprocessing',
                        ]) role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-justified-cheque-processing"
                            aria-controls="navs-justified-cheque-processing" aria-selected="false">
                            <i class="bi bi-7-circle-fill me-1_5"></i> Cheque Processing
                        </button>
                    </li>
                </ul>
                <div wire:ignore.self class="tab-content">
                    <div @class([
                        'tab-pane fade',
                        'show active' => $this->active_pane === 'procurement1',
                    ]) id="navs-justified-procurement1" role="tabpanel">

                        <div id="procurementView1">
                            <form wire:submit.prevent="edit">
                                @can('edit-requisition')
                                    <div class="row text-center">
                                        <div x-show="!isEditingProcurement1">
                                            <button type="button" @click="isEditingProcurement1 = ! isEditingProcurement1"
                                                class="btn btn-dark waves-effect waves-light" style="width: 100px">
                                                <span class="tf-icons ri-edit-box-fill me-1_5"></span>Edit
                                            </button>
                                        </div>
                                    </div>
                                @endcan

                                <div x-transition x-show="!isEditingProcurement1">

                                    {{-- 🔹 Row 1: Requisition Number & Requesting Unit --}}
                                    <div class="row mt-8">
                                        <div class="col-md-6">
                                            <label><strong>Requisition Number:</strong>
                                                {{ $this->requisition_no }}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label><strong>Requesting Unit:</strong>
                                                {{ $this->requisition->department->name }}</label>
                                        </div>
                                    </div>

                                    {{-- 🔹 Row 2: File Number / Form & Item --}}
                                    <div class="row mt-6">
                                        <div class="col">
                                            <label><strong>File Number / Form:</strong> {{ $this->file_no }}</label>
                                        </div>
                                        <div class="col">
                                            <label><strong>Item(s):</strong> {{ $this->item }}</label>
                                        </div>
                                    </div>

                                    {{-- 🔹 Row 3: Source of Funds & Date Received by Procurement --}}
                                    <div class="row mt-6">
                                        <div class="col-md-6">
                                            <label><strong>Source of Funds:</strong>
                                                {{ $this->source_of_funds }}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label><strong>Date Received by Procurement:</strong>
                                                {{ $this->getFormattedDate($this->requisition->date_received_procurement) }}</label>
                                        </div>
                                    </div>

                                    {{-- 🔹 Row 4: Assigned To & Date Assigned to Officer --}}
                                    <div class="row mt-6">
                                        <div class="col">
                                            <label><strong>Assigned To:</strong>
                                                {{ $this->requisition->procurement_officer->name ?? 'Not Assigned' }}</label>
                                        </div>
                                        <div class="col">
                                            <label><strong>Date Assigned to Officer:</strong>
                                                {{ $this->getFormattedDateAssigned() }}</label>
                                        </div>
                                    </div>

                                    {{-- 🔹 Row 6: Date Sent to AOV Procurement --}}
                                    <div class="row mt-6">
                                        <div class="col-md-6">
                                            <label><strong>Date Sent to AOV Procurement:</strong>
                                                {{ $this->getFormattedDate($this->date_sent_aov_procurement) }}</label>
                                        </div>
                                    </div>

                                    {{-- 🔹 Row 7: Site Visit & Site Visit Date --}}
                                    <div class="row mt-4 align-items-center">
                                        <div class="col-md-6">
                                            <label><strong>Site Visit Required:</strong>
                                                @if ($this->requisition->site_visit)
                                                    <i class="fa-solid fa-check text-success"></i>
                                                @else
                                                    <i class="fa-solid fa-xmark text-danger"></i>
                                                @endif
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label><strong>Site Visit Date:</strong>
                                                {{ $this->getFormattedDate($this->requisition->site_visit_date) }}</label>
                                        </div>
                                    </div>

                                    {{-- 🔹 Row 7.5: Tender Type --}}
                                    <div class="row mt-4 align-items-center">
                                        <div class="col-md-6">
                                            <label><strong>Tender Type:</strong>
                                                {{ $this->requisition->tender_type }}
                                            </label>
                                        </div>
                                    </div>

                                    {{-- 🔹 Row 7: Site Visit & Site Visit Date --}}
                                    <div class="row mt-4 align-items-center">
                                        <div class="col-md-6">
                                            <label><strong>Note to PS:</strong>
                                                @if ($this->requisition->note_to_ps)
                                                    <i class="fa-solid fa-check text-success"></i>
                                                @else
                                                    <i class="fa-solid fa-xmark text-danger"></i>
                                                @endif
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label><strong>Note to PS Date:</strong>
                                                {{ $this->getFormattedDate($this->requisition->note_to_ps_date) }}</label>
                                        </div>
                                    </div>

                                    {{-- 🔹 Row 8: Tender Issue & Deadline Dates --}}
                                    <div class="row mt-6">
                                        <div class="col">
                                            <label><strong>Tender/RFQ Issue Date:</strong>
                                                {{ $this->getFormattedDate($this->requisition->tender_issue_date) }}</label>
                                        </div>
                                        <div class="col">
                                            <label><strong>Tender/RFQ Deadline Date:</strong>
                                                {{ $this->getFormattedDate($this->requisition->tender_deadline_date) }}</label>
                                        </div>
                                    </div>

                                    {{-- 🔹 Row 9: Evaluation Start & End Dates --}}
                                    <div class="row mt-6">
                                        <div class="col">
                                            <label><strong>Evaluation Start Date:</strong>
                                                {{ $this->getFormattedDate($this->requisition->evaluation_start_date) }}</label>
                                        </div>
                                        <div class="col">
                                            <label><strong>Evaluation End Date:</strong>
                                                {{ $this->getFormattedDate($this->requisition->evaluation_end_date) }}</label>
                                        </div>
                                    </div>

                                    {{-- 🔹 Row 5: Actual Cost & Funding Availability --}}
                                    {{-- <div class="row mt-6">
                                        <div class="col">
                                            <label><strong>Actual Cost:</strong>
                                                ${{ number_format($this->actual_cost, 2) }}</label>
                                        </div>
                                        <div class="col">
                                            <label><strong>Funding Availability:</strong>
                                                {{ $this->funding_availability }}</label>
                                        </div>
                                    </div> --}}

                                    {{-- 🔹 Row 10: Date sent to DPS & PS Approval --}}
                                    <div class="row mt-6">
                                        <div class="col">
                                            <label><strong>Date sent to DPS:</strong>
                                                {{ $this->getFormattedDateSentPs() }}</label>
                                        </div>
                                        <div class="col">
                                            <label><strong>PS Approval:</strong>
                                                @if ($this->ps_approval == 'Pending')
                                                    <span class="badge rounded-pill bg-danger fs-6">Pending</span>
                                                @else
                                                    {{ $this->ps_approval }}
                                                @endif
                                            </label>
                                        </div>
                                    </div>

                                    {{-- 🔹 Row 10.5: PS Approval Date --}}
                                    @if ($this->ps_approval == 'Approved')
                                        <div class="row mt-6">
                                            <div class="col-md-6">
                                                <label><strong>PS Approval Date:</strong>
                                                    {{ $this->getFormattedDate($this->requisition->ps_approval_date) }}</label>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- 🔹 Row 11: Vendors & Amounts --}}
                                    @if ($this->ps_approval == 'Approved')
                                        @foreach ($this->requisition->vendors as $index => $vendor)
                                            <div class="row mt-6">
                                                <div class="col">
                                                    <label><strong>Vendor #{{ $index + 1 }}:</strong>
                                                        {{ $vendor['vendor_name'] }}</label>
                                                </div>
                                                <div class="col">
                                                    <label><strong>Amount:</strong>
                                                        ${{ number_format($vendor['amount'], 2) }}</label>
                                                </div>
                                            </div>
                                        @endforeach

                                        @if (count($vendors) > 0)
                                            <div class="row mt-6">
                                                <div class="col">
                                                    <label><strong>Total Amount:</strong>
                                                        ${{ number_format($this->totalAmount, 2) }}</label>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    {{-- 🔹 Row 12: Date Sent to Cost & Budgeting --}}
                                    @if ($this->sent_to_cb)
                                        <div class="row mt-6">
                                            <div class="col-md-6">
                                                <label><strong>Date Sent to Cost & Budgeting:</strong>
                                                    {{ $this->getFormattedDateSentCB() }}</label>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- 🔹 Row 13: Reason for Denial --}}
                                    @if ($this->ps_approval == 'Approval Denied')
                                        <div class="row mt-6">
                                            <div class="col">
                                                <label><strong>Reason for Denial:</strong>
                                                    {{ $this->denied_note }}</label>
                                            </div>
                                        </div>
                                    @endif

                                </div>


                                <div x-transition x-show="isEditingProcurement1">

                                    <div class="row mt-8">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="requisition_no" type="text"
                                                    class="form-control @error('requisition_no')is-invalid @enderror"
                                                    id="floatingInput" placeholder="Requisition Number"
                                                    aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">Requisition Number</label>
                                            </div>
                                            @error('requisition_no')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <div wire:ignore>
                                                <label style="width:100%" for="unitSelect">Requesting Unit:</label>
                                                <select wire:model="requesting_unit"
                                                    class="js-example-basic-single form-control" id="unitSelect"
                                                    style="width: 100%">
                                                    <option value="" selected>Select a Unit</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}">{{ $department->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('requesting_unit')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mt-6">
                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="file_no" type="text"
                                                    class="form-control @error('file_no')is-invalid @enderror"
                                                    id="floatingInput" placeholder="File Number / Form"
                                                    aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">File Number / Form</label>
                                            </div>
                                            @error('file_no')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>

                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="item" type="text"
                                                    class="form-control @error('item')is-invalid @enderror"
                                                    id="floatingInput" placeholder="ex. SSL Certificate"
                                                    aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">Item(s)</label>
                                            </div>
                                            @error('item')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mt-6">
                                        <div class="col-md-6">
                                            <div wire:ignore>
                                                <label style="width:100%" for="sofSelect">Source of Funds:</label>
                                                <select wire:model="source_of_funds"
                                                    class="js-example-basic-single form-control" id="sofSelect"
                                                    style="width: 100%">
                                                    <option value="" selected>Select a Vote</option>
                                                    @foreach ($votes as $vote)
                                                        <option value="{{ $vote->number }}">{{ $vote->number }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('source_of_funds')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="date_received_procurement"
                                                    type="date"
                                                    class="form-control @error('date_received_procurement')is-invalid @enderror"
                                                    id="floatingInput" aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">Date Received by Procurement</label>
                                            </div>
                                            @error('date_received_procurement')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mt-6">
                                        <div class="col">
                                            <div class="form-floating form-floating-outline mb-6">
                                                <select wire:model="assigned_to"
                                                    class="form-select @error('assigned_to')is-invalid @enderror"
                                                    id="exampleFormControlSelect1"
                                                    aria-label="Default select example">
                                                    <option value="" selected>Select Employee</option>
                                                    @foreach ($staff as $staff)
                                                        <option value="{{ $staff->id }}">{{ $staff->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label for="exampleFormControlSelect1">Assigned To</label>
                                                @error('assigned_to')
                                                    <div class="text-danger"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="date_assigned" type="date"
                                                    class="form-control @error('date_assigned')is-invalid @enderror"
                                                    id="floatingInput" aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">Date Assigned to Officer</label>
                                            </div>
                                            @error('date_assigned')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mt-6">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="date_sent_aov_procurement"
                                                    type="date"
                                                    class="form-control @error('date_sent_aov_procurement')is-invalid @enderror"
                                                    id="dateSentAOVInput"
                                                    placeholder="Date Sent to AOV Procurement" />
                                                <label for="dateSentAOVInput">Date Sent to AOV Procurement</label>
                                            </div>
                                            @error('date_sent_aov_procurement')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div x-data="{ siteVisit: $wire.entangle('site_visit'), noteToPs: $wire.entangle('note_to_ps') }">
                                        <div class="row mt-4 align-items-center">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="siteVisitCheck" x-model="siteVisit">
                                                    <label class="form-check-label" for="siteVisitCheck">Site Visit
                                                        Required</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6" x-show="siteVisit" x-transition>
                                                <div class="form-floating form-floating-outline">
                                                    <input autocomplete="off" wire:model="site_visit_date"
                                                        type="date"
                                                        class="form-control @error('site_visit_date')is-invalid @enderror"
                                                        id="siteVisitDateInput" placeholder="Site Visit Date" />
                                                    <label for="siteVisitDateInput">Site Visit Date</label>
                                                </div>
                                                @error('site_visit_date')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mt-4 align-items-center">
                                            <div class="col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <select wire:model="tender_type" class="form-select">
                                                        <option value="">Select Tender Type</option>
                                                        <option value="Open Tender">Open Tender</option>
                                                        <option value="Sole Select">Sole Select</option>
                                                        <option value="Selective Tender">Selective Tender</option>
                                                    </select>
                                                    <label>Tender Type</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4 align-items-center" x-show="siteVisit" x-transition>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="noteToPsCheck" x-model="noteToPs">
                                                    <label class="form-check-label" for="noteToPsCheck">Note to
                                                        PS</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6" x-show="noteToPs" x-transition>
                                                <div class="form-floating form-floating-outline">
                                                    <input autocomplete="off" wire:model="note_to_ps_date"
                                                        type="date"
                                                        class="form-control @error('note_to_ps_date')is-invalid @enderror"
                                                        id="noteToPsDateInput" placeholder="Date Sent to PS" />
                                                    <label for="noteToPsDateInput">Date Note Sent to PS</label>
                                                </div>
                                                @error('note_to_ps_date')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-6">
                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="tender_issue_date"
                                                    type="date"
                                                    class="form-control @error('tender_issue_date')is-invalid @enderror"
                                                    id="tenderIssueDateInput" placeholder="Tender Issue Date" />
                                                <label for="tenderIssueDateInput">Tender/RFQ Issue Date</label>
                                            </div>
                                            @error('tender_issue_date')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>

                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="tender_deadline_date"
                                                    type="date"
                                                    class="form-control @error('tender_deadline_date')is-invalid @enderror"
                                                    id="tenderDeadlineDateInput" placeholder="Tender Deadline Date" />
                                                <label for="tenderDeadlineDateInput">Tender/RFQ Deadline Date</label>
                                            </div>
                                            @error('tender_deadline_date')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mt-6">
                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="evaluation_start_date"
                                                    type="date"
                                                    class="form-control @error('evaluation_start_date')is-invalid @enderror"
                                                    id="evaluationStartDateInput"
                                                    placeholder="Evaluation Start Date" />
                                                <label for="evaluationStartDateInput">Evaluation Start Date</label>
                                            </div>
                                            @error('evaluation_start_date')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>

                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="evaluation_end_date"
                                                    type="date"
                                                    class="form-control @error('evaluation_end_date')is-invalid @enderror"
                                                    id="evaluationEndDateInput" placeholder="Evaluation End Date" />
                                                <label for="evaluationEndDateInput">Evaluation End Date</label>
                                            </div>
                                            @error('evaluation_end_date')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- <div class="row mt-6">
                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="actual_cost" type="number"
                                                    step="0.01"
                                                    class="form-control @error('actual_cost')is-invalid @enderror"
                                                    id="actualCostInput" placeholder="Enter Actual Cost" />
                                                <label for="actualCostInput">Actual Cost</label>
                                            </div>
                                            @error('actual_cost')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col">
                                            <div wire:ignore>
                                                <label style="width:100%" for="fundingSelect">Funding
                                                    Availability:</label>
                                                <select wire:model="funding_availability"
                                                    class="js-example-basic-single form-control" id="fundingSelect"
                                                    style="width: 100%">
                                                    <option value="" selected>Select a Vote</option>
                                                    @foreach ($votes as $vote)
                                                        <option value="{{ $vote->number }}">{{ $vote->number }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('funding_availability')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    <div class="row mt-6">
                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="date_sent_dps" type="date"
                                                    class="form-control @error('date_sent_dps')is-invalid @enderror"
                                                    id="floatingInput" aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">Date sent to DPS</label>
                                            </div>
                                            @error('date_sent_dps')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>

                                        <div class="col">
                                            <div class="form-floating form-floating-outline mb-6">
                                                <select required wire:model="ps_approval"
                                                    class="form-select @error('ps_approval')is-invalid @enderror"
                                                    id="exampleFormControlSelect1"
                                                    aria-label="Default select example">
                                                    <option value="Not Sent" selected>Not Sent</option>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Approved">Approved</option>
                                                    <option value="Approval Denied">Approval Denied</option>
                                                </select>
                                                <label for="exampleFormControlSelect1">PS Approval</label>
                                                @error('ps_approval')
                                                    <div class="text-danger"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2" x-show="ps_approval == 'Approved'" x-transition>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="ps_approval_date"
                                                    type="date"
                                                    class="form-control @error('ps_approval_date')is-invalid @enderror"
                                                    id="psApprovalDateInput" aria-describedby="floatingInputHelp" />
                                                <label for="psApprovalDateInput">PS Approval Date</label>
                                            </div>
                                            @error('ps_approval_date')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Vendors Section --}}
                                    <div class="row mt-2" x-show="ps_approval == 'Approved'" x-transition>
                                        <h4 class="text-center fw-bold">Vendors</h4>
                                        @forelse ($vendors as $index => $vendor)
                                            <div class="row mx-auto mt-2">
                                                <div class="col">
                                                    <div class="form-floating form-floating-outline">
                                                        <input autocomplete="off"
                                                            wire:model="vendors.{{ $index }}.vendor_name"
                                                            type="text"
                                                            class="form-control @error('vendors.' . $index . '.vendor_name')is-invalid @enderror" />
                                                        <label>Vendor Name</label>
                                                    </div>
                                                    @error('vendors.' . $index . '.vendor_name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col">
                                                    <div class="form-floating form-floating-outline">
                                                        <input autocomplete="off"
                                                            wire:model="vendors.{{ $index }}.amount"
                                                            type="number" step="0.01"
                                                            class="form-control @error('vendors.' . $index . '.amount')is-invalid @enderror" />
                                                        <label>Amount</label>
                                                    </div>
                                                    @error('vendors.' . $index . '.amount')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-1">
                                                    <button type="button"
                                                        wire:click="removeVendor({{ $index }})"
                                                        class="btn rounded-pill btn-icon btn-danger mx-auto mt-2">
                                                        <span class="tf-icons ri-delete-bin-2-line"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-center">Click the button below to add a vendor</p>
                                        @endforelse
                                        <button type="button" x-on:click="$wire.addVendor"
                                            class="btn rounded-pill btn-icon btn-primary mx-auto mt-2">
                                            <span class="tf-icons ri-add-line ri-22px"></span>
                                        </button>
                                    </div>

                                    {{-- Reason for Denial --}}
                                    <div class="row" x-show="ps_approval == 'Approval Denied'" x-transition>
                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="denied_note" type="text"
                                                    class="form-control @error('denied_note')is-invalid @enderror" />
                                                <label>Reason for Denial</label>
                                            </div>
                                            @error('denied_note')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row text-center mt-6">
                                        <div x-show="isEditingProcurement1">
                                            <button class="btn btn-primary waves-effect waves-light"
                                                style="width: 100px">
                                                <span class="tf-icons ri-save-3-line me-1_5"></span>Save
                                            </button>
                                            &nbsp;
                                            <button type="button"
                                                @click="isEditingProcurement1 = !isEditingProcurement1"
                                                class="btn btn-dark waves-effect waves-light" style="width: 100px">
                                                <span class="tf-icons ri-close-circle-line me-1_5"></span>Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </form>

                            @can('edit-requisition')
                                @if (!$this->sent_to_cb)
                                    <div class="row mt-8" x-show="!isEditingProcurement1">
                                        <button @disabled($this->isSendCBButtonDisabled)
                                            wire:confirm="Are you sure you want to send to cost & budgeting?"
                                            wire:loading.attr="disabled" type="button" wire:click="sendToCB"
                                            class="btn btn-success waves-effect waves-light m-auto" style="width: 300px;">
                                            <span class="tf-icons ri-mail-send-line me-1_5"></span>Send to Cost & Budgeting

                                            <div wire:loading class="spinner-border spinner-border-lg text-white mx-2"
                                                role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </button>
                                    </div>
                                @endif
                            @endcan
                        </div>
                    </div>
                    <div wire:ignore.self @class([
                        'tab-pane fade',
                        'show active' => $this->active_pane === 'cost_and_budgeting',
                    ]) id="navs-justified-cost_budgeting"
                        role="tabpanel">
                        <div>



                            @forelse ($requisition->vendors as $vendor)
                                <div class="divider">
                                    <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}</div>
                                </div>

                                <div class="row mt-8">

                                    <div class="col mx-5">
                                        <label><strong>Date Request Sent to Ministry of Finance:
                                            </strong>{{ $this->getFormattedDate($vendor['date_sent_request_mof']) }}</label>
                                    </div>

                                    <div class="col mx-5">
                                    </div>
                                </div>

                                <div class="row mt-7">

                                    <div class="col mx-5">
                                        <label><strong>Request Category:</strong>
                                            {{ $vendor['request_category'] }}</label>
                                    </div>

                                    <div class="col mx-5">
                                        <label><strong>Request Number:</strong> {{ $vendor['request_no'] }}</label>
                                    </div>
                                </div>

                                <div class="row mt-7">

                                    <div class="col mx-5">
                                        <label><strong>Release Type:</strong> {{ $vendor['release_type'] }}</label>
                                    </div>

                                    <div class="col mx-5">
                                        <label><strong>Release Number:</strong> {{ $vendor['release_no'] }}</label>
                                    </div>
                                </div>

                                <div class="row mt-7">

                                    <div class="col mx-5">
                                        <label><strong>Release Date:</strong>
                                            {{ $this->getFormattedDate($vendor['release_date']) }}
                                    </div>

                                    <div class="col mx-5">
                                        <label><strong>Change of Vote Number:</strong>
                                            @foreach ($vendor['votes'] as $vote)
                                                <span class="badge bg-label-primary">{{ $vote['number'] }}</span>
                                            @endforeach
                                        </label>
                                    </div>
                                </div>
                            @empty


                                <div class="row mt-8">

                                    <div class="col mx-5">
                                        <label><strong>Date Request Sent to Ministry of Finance:
                                            </strong></label>
                                    </div>

                                    <div class="col mx-5">
                                    </div>
                                </div>

                                <div class="row mt-7">

                                    <div class="col mx-5">
                                        <label><strong>Request Category:</strong>
                                        </label>
                                    </div>

                                    <div class="col mx-5">
                                        <label><strong>Request Number:</strong></label>
                                    </div>
                                </div>

                                <div class="row mt-7">

                                    <div class="col mx-5">
                                        <label><strong>Release Type:</strong></label>
                                    </div>

                                    <div class="col mx-5">
                                        <label><strong>Release Number:</strong></label>
                                    </div>
                                </div>

                                <div class="row mt-7">

                                    <div class="col mx-5">
                                        <label><strong>Release Date:</strong>
                                        </label>
                                    </div>

                                    <div class="col mx-5">
                                        <label><strong>Change of Vote Number:</strong></label>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div wire:ignore.self @class([
                        'tab-pane fade',
                        'show active' => $this->active_pane === 'procurement2',
                    ]) id="navs-justified-procurement2"
                        role="tabpanel">


                        <div id="procurementView2">
                            <form wire:submit.prevent="editProcurement2">
                                @can('edit-requisition')
                                    <div class="row text-center">
                                        <div x-show="!isEditingProcurement2">
                                            <button type="button"
                                                @click="isEditingProcurement2 = ! isEditingProcurement2"
                                                class="btn btn-dark waves-effect waves-light" style="width: 100px">
                                                <span class="tf-icons ri-edit-box-fill me-1_5"></span>Edit
                                            </button>
                                        </div>
                                    </div>
                                @endcan

                                <div x-transition x-show="!isEditingProcurement2">


                                    @forelse ($requisition->vendors as $vendor)
                                        <div class="row mt-5">
                                            <div class="divider">
                                                <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}
                                                </div>
                                            </div>

                                            @if (!$this->requisition->is_first_pass)
                                                <div>
                                                    <div class="col mx-5">
                                                        <a href="javascript:void(0);"
                                                            wire:click="displayInvoicesModal({{ $vendor['id'] }})">
                                                            Invoices
                                                            ({{ $invoice_count_buttons[$vendor['id']] ?? 0 }})
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="row mt-8">

                                                <div class="col mx-5">
                                                    <label><strong>Purchase Order Number:
                                                        </strong>{{ $vendor['purchase_order_no'] }}</label>
                                                </div>

                                                <div class="col mx-5">
                                                    <label><strong>ETA:</strong>
                                                        {{ $this->getFormattedDate($vendor['eta']) }} </label>
                                                </div>
                                            </div>

                                            <div class="row mt-7">

                                                <div class="col mx-5">
                                                    <label><strong>Date Sent for Commitment:</strong>
                                                        {{ $this->getFormattedDate($vendor['date_sent_commit']) }}</label>
                                                </div>

                                                <div class="col mx-5">
                                                    <label><strong>Date Sent to AP:</strong>
                                                        {{ $this->getFormattedDate($vendor['date_sent_ap']) }}</label>
                                                </div>
                                            </div>

                                        </div>

                                        @if (!$this->requisition->is_first_pass)
                                            <div class="row mt-5">
                                                @can('edit-requisition')
                                                    @if (!$vendor['sent_to_ap'])
                                                        <div class="row mt-8">
                                                            <button @disabled($this->isProcurement2ButtonDisabled($vendor))
                                                                wire:confirm="Are you sure you want to send to accounts payable?"
                                                                wire:target="sendToAP({{ $vendor['id'] }})"
                                                                wire:loading.attr="disabled" type="button"
                                                                wire:click="sendToAP({{ $vendor['id'] }})"
                                                                class="btn btn-success waves-effect waves-light m-auto"
                                                                style="width: 300px">
                                                                <span class="tf-icons ri-mail-send-line me-1_5"></span>Send
                                                                to
                                                                Accounts
                                                                Payable

                                                                <div wire:loading
                                                                    wire:target="sendToAP({{ $vendor['id'] }})"
                                                                    class="spinner-border spinner-border-lg text-white mx-2"
                                                                    role="status">
                                                                    <span class="visually-hidden">Loading...</span>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    @endif
                                                @endcan
                                            </div>
                                        @endif
                                    @empty
                                        <div class="row mt-5">

                                            <div class="row mt-8">

                                                <div class="col mx-5">
                                                    <label><strong>Purchase Order Number:
                                                        </strong></label>
                                                </div>

                                                <div class="col mx-5">
                                                    <label><strong>ETA:</strong></label>
                                                </div>
                                            </div>

                                            <div class="row mt-7">

                                                <div class="col mx-5">
                                                    <label><strong>Date Sent for Commitment:</strong></label>
                                                </div>

                                                <div class="col mx-5">
                                                    <label><strong>Date Sent to AP:</strong></label>
                                                </div>
                                            </div>

                                        </div>
                                    @endforelse
                                    @can('edit-requisition')
                                        @if ($this->requisition->is_first_pass && count($vendors) > 0 && !$this->requisition->sent_to_ap_first_pass)
                                            <div class="row mt-8">
                                                <button @disabled($this->isSendAllToAPButtonDisabled)
                                                    wire:confirm="Are you sure you want to send to accounts payable?"
                                                    wire:target="sendToAP({{ $vendors[0]['id'] }})"
                                                    wire:loading.attr="disabled" type="button"
                                                    wire:click="sendToAP({{ $vendors[0]['id'] }})"
                                                    class="btn btn-success waves-effect waves-light m-auto"
                                                    style="width: 300px">
                                                    <span class="tf-icons ri-mail-send-line me-1_5"></span>Send to
                                                    Accounts Payable
                                                    <div wire:loading wire:target="sendToAP({{ $vendors[0]['id'] }})"
                                                        class="spinner-border spinner-border-lg text-white mx-2"
                                                        role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </button>
                                            </div>
                                        @endif
                                    @endcan
                                </div>

                                <div x-transition x-show="isEditingProcurement2">
                                    @foreach ($this->requisition->vendors as $index => $vendor)
                                        <div class="accordion mt-8" id="accordion{{ $index }}"
                                            style="margin-top: 15px">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button
                                                        x-on:click="$wire.toggleAccordionView({{ $index }})"
                                                        class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse{{ $index }}"
                                                        aria-expanded="true"
                                                        aria-controls="collapse{{ $index }}">
                                                        <strong>Vendor: {{ $vendor['vendor_name'] }} |
                                                            ${{ number_format($vendor['amount'], 2) }}</strong>
                                                    </button>
                                                </h2>
                                                <div id="collapse{{ $index }}"
                                                    class="accordion-collapse collapse {{ $vendor['accordionView'] }}"
                                                    data-bs-parent="#accordion{{ $index }}">
                                                    <div class="accordion-body">
                                                        <div class="row mt-8">

                                                            <div class="col-md-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input autocomplete="off"
                                                                        wire:model="vendors.{{ $index }}.purchase_order_no"
                                                                        type="text"
                                                                        class="form-control @error('vendors.' . $index . '.purchase_order_no')is-invalid @enderror"
                                                                        id="floatingInput"
                                                                        placeholder="Purchase Order Number"
                                                                        aria-describedby="floatingInputHelp" />
                                                                    <label for="floatingInput">Purchase Order
                                                                        Number</label>
                                                                </div>
                                                                @error('vendors.' . $index . '.purchase_order_no')
                                                                    <div class="text-danger"> {{ $message }} </div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input autocomplete="off"
                                                                        wire:model="vendors.{{ $index }}.eta"
                                                                        type="date"
                                                                        class="form-control @error('vendors.' . $index . '.eta')is-invalid @enderror"
                                                                        id="floatingInput"
                                                                        aria-describedby="floatingInputHelp" />
                                                                    <label for="floatingInput">ETA</label>
                                                                </div>
                                                                @error('vendors.' . $index . '.eta')
                                                                    <div class="text-danger"> {{ $message }} </div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="row mt-7">

                                                            <div class="col-md-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input autocomplete="off"
                                                                        wire:model="vendors.{{ $index }}.date_sent_commit"
                                                                        type="date"
                                                                        class="form-control @error('vendors.' . $index . '.date_sent_commit')is-invalid @enderror"
                                                                        id="floatingInput"
                                                                        aria-describedby="floatingInputHelp" />
                                                                    <label for="floatingInput">Date Sent for
                                                                        Commitment</label>
                                                                </div>
                                                                @error('vendors.' . $index . '.date_sent_commit')
                                                                    <div class="text-danger"> {{ $message }} </div>
                                                                @enderror
                                                            </div>


                                                            <div class="col-md-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input autocomplete="off"
                                                                        wire:model="vendors.{{ $index }}.date_sent_ap"
                                                                        type="date"
                                                                        class="form-control @error('vendors.' . $index . '.date_sent_ap')is-invalid @enderror"
                                                                        id="floatingInput"
                                                                        aria-describedby="floatingInputHelp" />
                                                                    <label for="floatingInput">Date Sent to
                                                                        AP</label>
                                                                </div>
                                                                @error('vendors.' . $index . '.date_sent_ap')
                                                                    <div class="text-danger"> {{ $message }} </div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        @if (!$this->requisition->is_first_pass)
                                                            <div class="d-flex justify-content-center mt-8">
                                                                <button type="button"
                                                                    wire:click="displayInvoicesModal({{ $vendor['id'] }})"
                                                                    class="btn btn-success waves-effect waves-light">
                                                                    Invoices
                                                                    ({{ $invoice_count_buttons[$vendor['id']] ?? 0 }})
                                                                </button>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="row text-center mt-8">

                                        <div x-show="isEditingProcurement2">
                                            <button class="btn btn-primary waves-effect waves-light"
                                                style="width: 100px">
                                                <span class="tf-icons ri-save-3-line me-1_5"></span>Save
                                            </button>
                                            &nbsp;
                                            <button type="button"
                                                @click="isEditingProcurement2 = ! isEditingProcurement2"
                                                class="btn btn-dark waves-effect waves-light" style="width: 100px">
                                                <span class="tf-icons ri-close-circle-line me-1_5"></span>Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div>

                    <div @class([
                        'tab-pane fade',
                        'show active' => $this->active_pane === 'accounts_payable',
                    ]) id="navs-justified-accounts_payable" role="tabpanel">

                        <div>

                            @forelse ($requisition->vendors as $vendor)
                                <div class="row mt-2">
                                    <div class="divider">
                                        <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}</div>
                                    </div>

                                    <div class="row mt-8">

                                        <div class="col mx-5">
                                            <label><strong>Date Received From Procurement For Commitment :</strong>
                                                {{ $this->getFormattedDate($vendor['date_received_ap']) }}</label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Date Sent to Vote Control For Commitment:</strong>
                                                {{ $this->getFormattedDate($vendor['date_sent_vc']) }}</label>
                                        </div>
                                    </div>

                                    @if (!$this->requisition->is_first_pass)
                                        <div class="row mt-8">

                                            <div class="col mx-5">
                                                <label><strong>Date Received From Procurement For Invoices :</strong>
                                                    {{ $this->getFormattedDate($vendor['date_received_ap_invoices']) }}</label>
                                            </div>

                                            <div class="col mx-5">
                                                <label><strong>Date Sent to Vote Control For Invoices:</strong>
                                                    {{ $this->getFormattedDate($vendor['date_sent_vc_invoices']) }}</label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty

                                <div class="row mt-8">

                                    <div class="col mx-5">
                                        <label><strong>Date Received From Procurement For Commitment :</strong></label>
                                    </div>

                                    <div class="col mx-5">
                                        <label><strong>Date Sent to Vote Control For Commitment:</strong></label>
                                    </div>
                                </div>
                            @endforelse

                        </div>
                    </div>

                    <div @class([
                        'tab-pane fade',
                        'show active' => $this->active_pane === 'votecontrol',
                    ]) id="navs-justified-votecontrol" role="tabpanel">

                        <div>

                            @forelse ($requisition->vendors as $vendor)
                                <div class="row mt-2">
                                    <div class="divider">
                                        <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}</div>
                                    </div>

                                    <div class="row mt-2">

                                        <div class="col mx-5">
                                            <label><strong>Date Committed:</strong>
                                                {{ $this->getFormattedDate($vendor['date_committed_vc']) }}</label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Batch Number:</strong> {{ $vendor['batch_no'] }}</label>
                                        </div>
                                    </div>

                                    <div class="row mt-7">

                                        <div class="col mx-5">
                                            <label><strong>Voucher Number:
                                                </strong>{{ $vendor['voucher_no'] }}</label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Date Sent to Check Staff:</strong>
                                                {{ $this->getFormattedDate($vendor['date_sent_checkstaff']) }}</label>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="row mt-2">

                                    <div class="col mx-5">
                                        <label><strong>Batch Number:</strong></label>
                                    </div>

                                    <div class="col mx-5">
                                        <label><strong>Voucher Number:
                                            </strong></label>
                                    </div>
                                </div>

                                <div class="row mt-7">

                                    <div class="col mx-5">
                                        <label><strong>Date Sent to Check Staff:</strong></label>
                                    </div>

                                    <div class="col mx-5">
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div @class([
                        'tab-pane fade',
                        'show active' => $this->active_pane === 'checkroom',
                    ]) id="navs-justified-check-room" role="tabpanel">


                        <div>

                            @forelse ($requisition->vendors as $vendor)
                                <div class="row">
                                    <div class="divider">
                                        <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}</div>
                                    </div>

                                    <div class="row mt-2">

                                        <div class="col mx-5">
                                            <label><strong>Date Voucher Received from Vote Control:</strong>
                                                {{ $this->getFormattedDate($vendor['date_received_from_vc']) }}</label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Voucher Sent To:
                                                </strong>{{ $vendor['voucher_destination'] }}</label>
                                        </div>
                                    </div>

                                    @if ($vendor['voucher_destination'] == 'Internal Audit')
                                        <div class="row mt-8">

                                            <div class="col mx-5">
                                                <label><strong>Date Sent to Audit:</strong>
                                                    {{ $this->getFormattedDate($vendor['date_sent_audit']) }}</label>
                                            </div>

                                            <div class="col mx-5">
                                                <label><strong>Date Received from Audit:
                                                    </strong>{{ $this->getFormattedDate($vendor['date_received_from_audit']) }}</label>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row mt-8">

                                        <div class="col mx-5">
                                            <label><strong>Date Voucher Sent to Cheque Processing:</strong>
                                                {{ $this->getFormattedDate($vendor['date_sent_chequeprocessing']) }}</label>
                                        </div>

                                        <div class="col mx-5">
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="row mt-2">

                                    <div class="col mx-5">
                                        <label><strong>Date Voucher Received from Vote Control:</strong></label>
                                    </div>

                                    <div class="col mx-5">
                                        <label><strong>Voucher Sent To:
                                            </strong></label>
                                    </div>
                                </div>

                                <div class="row mt-8">

                                    <div class="col mx-5">
                                        <label><strong>Date Sent to Audit:</strong></label>
                                    </div>

                                    <div class="col mx-5">
                                        <label><strong>Date Received from Audit:
                                            </strong></label>
                                    </div>
                                </div>

                                <div class="row mt-8">

                                    <div class="col mx-5">
                                        <label><strong>Date Voucher Sent to Cheque Processing:</strong></label>
                                    </div>

                                    <div class="col mx-5">
                                    </div>
                                </div>
                            @endforelse

                        </div>
                    </div>

                    <div @class([
                        'tab-pane fade',
                        'show active' => $this->active_pane === 'chequeprocessing',
                    ]) id="navs-justified-cheque-processing" role="tabpanel">

                        <div>


                            <h5>Cheques ({{ count($this->cheques) }})</h5>
                            <table class="table table-bordered table-hover" style="margin-top: 10px">
                                <thead class="text-center">
                                    <tr>
                                        <th>Vendor</th>
                                        <th>Cheque Number</th>
                                        <th>Cheque Amount </th>
                                        <th>Cheque Date</th>
                                        <th>Date Sent to Dispatch</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($this->cheques as $cheque)
                                        <tr class="text-center">
                                            <td>{{ $cheque->vendor->vendor_name }}</td>
                                            <td>{{ $cheque->cheque_no }}</td>
                                            <td>${{ number_format($cheque->cheque_amount, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cheque->date_of_cheque)->format('d/m/Y') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($cheque->date_sent_dispatch)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align: center;">No cheques entered</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>


                            @if ($this->requisition->is_completed)
                                <div style="margin-top: 50px" class="row text-center">

                                    <h3><strong>Requisition Completed on
                                            {{ \Carbon\Carbon::parse($this->date_completed)->format('F jS, Y') }}</strong>
                                    </h3>

                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="divider" style="margin-top: 40px">
                <div class="divider-text">
                    <i class="fa-solid fa-file-pen fs-4"></i>
                </div>
            </div>

            <div class="row">
                <h4 class="text-center fw-bold">Status Log</h4>
            </div>

            @can('edit-requisition')
                <div class="row">
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addLogModal"
                        class="btn btn-primary waves-effect waves-light w-25 m-auto">
                        <span class="tf-icons ri-file-add-line me-1_5"></span>Add Log
                    </a>
                </div>
            @endcan

            <div class="row mt-8">
                <table class="table table-hover table-bordered w-100">
                    <thead>
                        <tr>
                            <th>Details</th>
                            <th class="text-center" style="width: 20%">Date</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($logs as $index => $log)
                            <tr>
                                <td>{{ $log->details }}</td>
                                <td class="text-center">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No logs added</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            <div id="file-uploads">

                <div class="divider" style="margin-top: 40px">
                    <div class="divider-text">
                        <i class="fa-solid fa-file-arrow-up fs-4"></i>
                    </div>
                </div>

                <div class="row">
                    <h4 class="text-center fw-bold">File Uploads</h4>
                </div>

                @can('edit-requisition')
                    <div class="row">
                        <div class="col" style="text-align: center;padding-bottom:10px">
                            @error('upload')
                                <div class="text-danger fw-bold"> {{ $message }} </div>
                            @enderror

                            <input wire:model="upload" type="file" class="form-control"
                                style="display: inline;width: 400px;height:45px">
                            <button wire:click.prevent="uploadFile()" class="btn btn-primary"
                                wire:loading.attr="disabled" wire:target="upload" style="width: 8rem"><i
                                    class="fas fa-plus me-2"></i> Upload</button>
                            <span wire:loading wire:target="upload">Uploading...</span>
                        </div>
                    </div>
                @endcan

                <div class="row ">

                    <div class="demo-inline-spacing d-flex justify-content-center align-items-center">
                        <div class="list-group list-group-flush" style="width: 500px">

                            @forelse ($uploads as $upload)
                                <div class="list-group list-group-flush list-group-item-action"
                                    style="width: 100%;cursor: default;">
                                    <div class="list-group-item d-flex justify-content-between align-items-center"
                                        style="border: none;">
                                        <a class="text-dark text-decoration-underline"
                                            href="{{ Storage::url($upload->file_path) }}"
                                            target="_blank">{{ $upload->file_name }}</a>
                                        {{-- <button type="button" class="btn btn-danger">
                                                <i class="ri-delete-bin-2-line me-1"></i> Delete
                                            </button> --}}
                                        @can('edit-requisition')
                                            <a href="javascript:void(0)"
                                                wire:confirm="Are you sure you want to delete this file?"
                                                wire:click="deleteFile({{ $upload->id }})">
                                                <i class="ri-close-large-line text-danger fw-bold"></i>
                                            </a>
                                        @endcan

                                    </div>
                                </div>
                            @empty
                                <div class="list-group list-group-flush list-group-item-action"
                                    style="width: 100%;cursor: default;">
                                    <div class="list-group-item" style="border: none;">
                                        <p class="text-center">No files uploaded</p>

                                    </div>
                                </div>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@script
    <script>
        $(document).ready(function() {

            $('#unitSelect').select2();

            $('#unitSelect').on('change', function() {
                var selectedValue = $(this).val(); // Get selected values as an array
                $wire.set('requesting_unit', selectedValue); // Pass selected values to Livewire
                $wire.set('active_pane', 'procurement1');
            });

            $('#fundingSelect').select2();
            $('#fundingSelect').on('change', function() {
                var selectedValue = $(this).val(); // Get selected values as an array
                $wire.set('funding_availability', selectedValue); // Pass selected values to Livewire
            });

            $('#sofSelect').select2();

            $('#sofSelect').on('change', function() {
                var selectedValue = $(this).val(); // Get selected values as an array
                $wire.set('source_of_funds', selectedValue); // Pass selected values to Livewire
                $wire.set('active_pane', 'procurement1');
            });
        });


        window.addEventListener('close-log-modal', event => {
            $('#addLogModal').modal('hide');
        })

        window.addEventListener('display-invoices-modal', event => {
            $('#invoiceModal').modal('show');
        })

        $wire.on('scrollToError', () => {
            // Wait for Livewire to finish rendering the error fields
            setTimeout(() => {
                const firstErrorElement = document.querySelector('.is-invalid');
                if (firstErrorElement) {
                    firstErrorElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    firstErrorElement.focus(); // Optional: Focus the field
                }
            }, 100); // Adding a small delay (100ms) before scrolling
        });

        $wire.on('preserveScroll', () => {
            // You can store the current scroll position before the update
            const scrollY = window.scrollY;

            // Reapply the scroll position after the update
            setTimeout(() => {
                window.scrollTo(0, scrollY);
            }, 100);
        });

        $('#addLogModal').on('shown.bs.modal', function() {
            $('#detailsInput').focus()
        })
    </script>
@endscript
