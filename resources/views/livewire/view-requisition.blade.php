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

            <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <a href="{{ route('requisitions.index') }}" class="btn btn-dark">
                    <i class="ri-arrow-left-circle-line me-1"></i> Back
                </a>

                <h1 class="h3 mb-0 text-gray-800" style="flex: 1; text-align: center;">
                    <strong>{{ $this->requisition_no }}</strong>
                    @if ($this->requisition->is_completed)
                        <span style="background-color: #47a102 !important;"
                            class="badge rounded-pill bg-success fs-5">Completed</span>
                        {{-- @else
                        <span style="background-color: #c6850c !important;"
                            class="badge rounded-pill bg-danger fs-5">{{ $this->requisition->requisition_status }}</span> --}}
                    @endif
                </h1>
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
                                @can('edit-records')
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
                                    <div class="row mt-8">

                                        <div class="col mx-5">
                                            <label><strong>Requisition Number:
                                                </strong>{{ $this->requisition_no }}</label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Requesting Unit:</strong>
                                                {{ $this->requisition->department->name }}</label>
                                        </div>
                                    </div>

                                    <div class="row mt-7">

                                        <div class="col mx-5">
                                            <label><strong>File Number / Form:</strong> {{ $this->file_no }}</label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Item:</strong> {{ $this->item }}</label>
                                        </div>

                                    </div>

                                    <div class="row mt-7">

                                        <div class="col mx-5">
                                            <label><strong>Source of Funds:</strong>
                                                {{ $this->source_of_funds }}</label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Assigned To:</strong>
                                                @if ($this->requisition->procurement_officer)
                                                    {{ $this->requisition->procurement_officer->name ?? 'Not Assigned' }}
                                            </label>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="row mt-7">

                                        <div class="col mx-5">
                                            <label><strong>Date Assigned to Officer:</strong>
                                                {{ $this->getFormattedDateAssigned() }}</label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Date sent to DPS:</strong>
                                                {{ $this->getFormattedDateSentPs() }}</label>
                                        </div>

                                    </div>

                                    <div class="row mt-7">

                                        <div class="col mx-5">
                                            <label><strong>PS Approval:</strong>
                                                @if ($this->ps_approval == 'Pending')
                                                    <span class="badge rounded-pill bg-danger fs-6">Pending</span>
                                                @else
                                                    {{ $this->ps_approval }}
                                                @endif
                                            </label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Date Received by Procurement:</strong>
                                                {{ $this->getFormattedDate($this->requisition->date_received_procurement) }}</label>
                                        </div>

                                    </div>

                                    @if ($this->ps_approval == 'Approved')
                                        @foreach ($this->vendors as $index => $vendor)
                                            <div class="row mt-7">

                                                <div class="col mx-5">
                                                    <label><strong>Vendor #{{ $index + 1 }}:</strong>
                                                        {{ $vendor['vendor_name'] }}</label>
                                                </div>

                                                <div class="col mx-5">
                                                    <label><strong>Amount:</strong>
                                                        ${{ number_format($vendor['amount'], 2) }}</label>
                                                </div>

                                            </div>
                                        @endforeach

                                        @if (count($vendors) > 0)
                                            <div class="row mt-7">

                                                <div class="col mx-5">
                                                    <label><strong>Total Amount:</strong>
                                                        ${{ number_format($this->totalAmount, 2) }}</label>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    @if ($this->sent_to_cb)
                                        <div class="row mt-7">

                                            <div class="col mx-5">
                                                <label><strong>Date Sent to Cost & Budgeting:</strong>
                                                    {{ $this->getFormattedDateSentCB() }}</label>
                                            </div>

                                            <div class="col mx-5">
                                            </div>

                                        </div>
                                    @endif

                                    @if ($this->ps_approval == 'Approval Denied')
                                        <div class="row mt-7">

                                            <div class="col mx-5">
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

                                    <div class="row mt-7">

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
                                                <label for="floatingInput">Item</label>
                                            </div>
                                            @error('item')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mt-7">

                                        <div class="col-md-6">
                                            <div wire:ignore>
                                                <label style="width:100%" for="sofSelect">Source of Funds:</label>

                                                <select wire:model="source_of_funds"
                                                    class="js-example-basic-single form-control" id="sofSelect"
                                                    style="width: 100%">
                                                    <option value="" selected>Select a Unit</option>
                                                    @foreach ($votes as $vote)
                                                        <option value="{{ $vote->number }}">{{ $vote->number }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            {{-- <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="source_of_funds" type="text"
                                                    class="form-control @error('source_of_funds')is-invalid @enderror"
                                                    id="floatingInput" placeholder="Source of Funds"
                                                    aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">Source of Funds</label>
                                            </div> --}}
                                            @error('source_of_funds')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>

                                        <div class="col">
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

                                    <div class="row mt-7">

                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="date_assigned" type="date"
                                                    class="form-control @error('date_assigned')is-invalid @enderror"
                                                    id="floatingInput" aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">Date Assigned</label>
                                            </div>
                                            @error('date_assigned')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </div>

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
                                    </div>

                                    <div class="row">


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

                                        <h4 class="text-center fw-bold">Vendors</h4>

                                        @forelse ($vendors as $index => $vendor)
                                            <div class="row mx-auto mt-2">
                                                <div class="col">
                                                    <div class="form-floating form-floating-outline">
                                                        <input autocomplete="off"
                                                            wire:model="vendors.{{ $index }}.vendor_name"
                                                            type="text"
                                                            class="form-control @error('vendors.' . $index . '.vendor_name')is-invalid @enderror"
                                                            id="floatingInput" placeholder="ex. Fujitsu"
                                                            aria-describedby="floatingInputHelp" />
                                                        <label for="floatingInput">Vendor Name</label>
                                                    </div>
                                                    @error('vendors.' . $index . '.vendor_name')
                                                        <div class="text-danger"> {{ $message }} </div>
                                                    @enderror
                                                </div>

                                                <div class="col">
                                                    <div class="form-floating form-floating-outline">
                                                        <input autocomplete="off"
                                                            wire:model="vendors.{{ $index }}.amount"
                                                            type="number" step="0.01"
                                                            class="form-control @error('vendors.' . $index . '.amount')is-invalid @enderror"
                                                            id="floatingInput" placeholder="0.00"
                                                            aria-describedby="floatingInputHelp" />
                                                        <label for="floatingInput">Amount</label>
                                                    </div>
                                                    @error('vendors.' . $index . '.amount')
                                                        <div class="text-danger"> {{ $message }} </div>
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

                                    <div class="row" x-show="ps_approval == 'Approval Denied'" x-transition>

                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input autocomplete="off" wire:model="denied_note" type="text"
                                                    class="form-control @error('denied_note')is-invalid @enderror"
                                                    id="floatingInput" placeholder="ex. Fujitsu"
                                                    aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">Reason for Denial</label>
                                            </div>
                                            @error('denied_note')
                                                <div class="text-danger"> {{ $message }} </div>
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
                                                @click="isEditingProcurement1 = ! isEditingProcurement1"
                                                class="btn btn-dark waves-effect waves-light" style="width: 100px">
                                                <span class="tf-icons ri-close-circle-line me-1_5"></span>Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            @can('edit-records')
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



                            @foreach ($vendors as $vendor)
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
                                            {{ $vendor['change_of_vote_no'] }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div wire:ignore.self @class([
                        'tab-pane fade',
                        'show active' => $this->active_pane === 'procurement2',
                    ]) id="navs-justified-procurement2"
                        role="tabpanel">


                        <div id="procurementView2">
                            <form wire:submit.prevent="editProcurement2">
                                @can('edit-records')
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


                                    @foreach ($vendors as $vendor)
                                        <div class="row mt-5">
                                            <div class="divider">
                                                <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}
                                                </div>
                                            </div>

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
                                                    <label><strong>Date Sent to Commit:</strong>
                                                        {{ $this->getFormattedDate($vendor['date_sent_commit']) }}</label>
                                                </div>

                                                <div class="col mx-5">
                                                    <label><strong>Invoice Number:</strong>
                                                        {{ $vendor['invoice_no'] }}</label>
                                                </div>
                                            </div>

                                            <div class="row mt-7">

                                                <div class="col mx-5">
                                                    <label><strong>Date of Invoice Received in the Department:</strong>
                                                        {{ $this->getFormattedDate($vendor['date_invoice_received']) }}</label>
                                                </div>

                                                <div class="col mx-5">
                                                    <label><strong>Date Sent to AP:</strong>
                                                        {{ $this->getFormattedDate($vendor['date_sent_ap']) }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-5">

                                            @can('edit-records')
                                                @if (!$vendor['sent_to_ap'])
                                                    <div class="row mt-8">
                                                        <button @disabled($this->isProcurement2ButtonDisabled($vendor))
                                                            wire:confirm="Are you sure you want to send to accounts payable?"
                                                            wire:target="sendToAP({{ $vendor['id'] }})"
                                                            wire:loading.attr="disabled" type="button"
                                                            wire:click="sendToAP({{ $vendor['id'] }})"
                                                            class="btn btn-success waves-effect waves-light m-auto"
                                                            style="width: 300px">
                                                            <span class="tf-icons ri-mail-send-line me-1_5"></span>Send to
                                                            Accounts
                                                            Payable

                                                            <div wire:loading wire:target="sendToAP({{ $vendor['id'] }})"
                                                                class="spinner-border spinner-border-lg text-white mx-2"
                                                                role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                        </button>
                                                    </div>
                                                @endif
                                            @endcan
                                        </div>
                                    @endforeach


                                </div>

                                <div x-transition x-show="isEditingProcurement2">
                                    @foreach ($this->vendors as $index => $vendor)
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
                                                        <strong>Vendor: {{ $vendor['vendor_name'] }} </strong>
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
                                                                    <label for="floatingInput">Date Sent to
                                                                        Commit</label>
                                                                </div>
                                                                @error('vendors.' . $index . '.date_sent_commit')
                                                                    <div class="text-danger"> {{ $message }} </div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input autocomplete="off"
                                                                        wire:model="vendors.{{ $index }}.invoice_no"
                                                                        type="text"
                                                                        class="form-control @error('vendors.' . $index . '.invoice_no')is-invalid @enderror"
                                                                        id="floatingInput"
                                                                        placeholder="Invoice Number"
                                                                        aria-describedby="floatingInputHelp" />
                                                                    <label for="floatingInput">Invoice
                                                                        Number</label>
                                                                </div>
                                                                @error('vendors.' . $index . '.invoice_no')
                                                                    <div class="text-danger"> {{ $message }} </div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="row mt-7">

                                                            <div class="col-md-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input autocomplete="off"
                                                                        wire:model="vendors.{{ $index }}.date_invoice_received"
                                                                        type="date"
                                                                        class="form-control @error('vendors.' . $index . '.date_invoice_received')is-invalid @enderror"
                                                                        id="floatingInput"
                                                                        aria-describedby="floatingInputHelp" />
                                                                    <label for="floatingInput">Date of Invoice
                                                                        Received in the Department</label>
                                                                </div>
                                                                @error('vendors.' . $index . '.date_invoice_received')
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


                                                        <div class="d-flex justify-content-center mt-8">
                                                            <button type="button"
                                                                wire:click="displayInvoicesModal({{ $vendor['id'] }})"
                                                                class="btn btn-success waves-effect waves-light">
                                                                Invoices
                                                                ({{ $invoice_count_buttons[$vendor['id']] ?? 0 }})
                                                            </button>
                                                        </div>

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

                            @foreach ($vendors as $vendor)
                                <div class="row mt-2">
                                    <div class="divider">
                                        <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}</div>
                                    </div>

                                    <div class="row mt-8">

                                        <div class="col mx-5">
                                            <label><strong>Date Received From Procurement :</strong>
                                                {{ $this->getFormattedDate($vendor['date_received_ap']) }}</label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Date Sent to Vote Control:</strong>
                                                {{ $this->getFormattedDate($vendor['date_sent_vc']) }}</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div @class([
                        'tab-pane fade',
                        'show active' => $this->active_pane === 'votecontrol',
                    ]) id="navs-justified-votecontrol" role="tabpanel">

                        <div>

                            @foreach ($vendors as $vendor)
                                <div class="row mt-2">
                                    <div class="divider">
                                        <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}</div>
                                    </div>

                                    <div class="row mt-2">

                                        <div class="col mx-5">
                                            <label><strong>Batch Number:</strong> {{ $vendor['batch_no'] }}</label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Voucher Number: </strong>{{ $vendor['voucher_no'] }}</label>
                                        </div>
                                    </div>

                                    <div class="row mt-7">

                                        <div class="col mx-5">
                                            <label><strong>Date Sent to Check Staff:</strong>
                                                {{ $this->getFormattedDate($vendor['date_sent_checkstaff']) }}</label>
                                        </div>

                                        <div class="col mx-5">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div @class([
                        'tab-pane fade',
                        'show active' => $this->active_pane === 'checkroom',
                    ]) id="navs-justified-check-room" role="tabpanel">


                        <div>

                            @foreach ($vendors as $vendor)
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
                            @endforeach

                        </div>
                    </div>

                    <div @class([
                        'tab-pane fade',
                        'show active' => $this->active_pane === 'chequeprocessing',
                    ]) id="navs-justified-cheque-processing" role="tabpanel">

                        <div>

                            @foreach ($vendors as $vendor)
                                <div class="row mt-1">
                                    <div class="divider">
                                        <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}</div>
                                    </div>

                                    <div class="row mt-2">

                                        <div class="col mx-5">
                                            <label><strong>Date Cheque Processed:</strong>
                                                {{ $this->getFormattedDate($vendor['date_cheque_processed']) }}</label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Cheque Number: </strong>{{ $vendor['cheque_no'] }}</label>
                                        </div>
                                    </div>

                                    <div class="row mt-2">

                                        <div class="col mx-5">
                                            <label><strong>Cheque Date:</strong>
                                                {{ $this->getFormattedDate($vendor['date_of_cheque']) }}</label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Date Cheque Sent to Cheque Dispatch:
                                                </strong>{{ $this->getFormattedDate($vendor['date_sent_dispatch']) }}</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


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

            @can('edit-records')
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
                            <th class="text-center" style="width: 20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($logs as $index => $log)
                            <tr>
                                <td>{{ $log->details }}</td>
                                <td class="text-center">

                                    <button @cannot('delete-records') disabled @endcannot
                                        wire:confirm="Are you sure you want to delete this log?"
                                        wire:click="deleteLog({{ $log->id }})" type="button"
                                        class="btn btn-danger">
                                        <i class="ri-delete-bin-2-line me-1"></i> Delete
                                    </button>

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

                @can('edit-records')
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
                                        @can('edit-records')
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

        $('#invoiceModal').on('hidden.bs.modal', function() {
            // $wire.refreshVendors();
        })
    </script>
@endscript
