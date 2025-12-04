<div>
    @include('livewire.add-item-modal')
    @include('livewire.edit-item-modal')
    @include('livewire.approve-form-hod-modal')
    @include('livewire.approve-form-reporting-officer')
    @include('livewire.decline-form-modal')
    @include('livewire.add-form-log')
    @include('livewire.send-to-hod-modal')
    @include('livewire.forward-form-modal')
    <div class="card">
        <div class="card-body" x-data="{ isEditing: $wire.entangle('isEditing') }">



            <form wire:submit.prevent="save">

                <div class="d-sm-flex align-items-center justify-content-between mb-5">

                    {{-- Left Column: Back Button --}}
                    <div class="col text-start">
                        <a href="{{ $backUrl }}" class="btn btn-primary">
                            <i class="ri-arrow-left-circle-line me-1"></i> Back
                        </a>
                    </div>

                    {{-- Center Column: Heading --}}
                    <div class="col text-center">
                        <h1 class="h3 mb-0 text-gray-800">
                            <strong>#{{ $requisitionForm->form_code }}</strong>
                        </h1>
                    </div>

                    {{-- Right Column: Save + Edit Buttons --}}
                    <div class="col text-end d-flex justify-content-end gap-2">

                        @if (
                            !$requisitionForm->date_sent_to_hod ||
                                $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_HOD ||
                                $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_PS ||
                                $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_DPS ||
                                $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_CMO ||
                                $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_PROCUREMENT)
                            @can('edit-requisition-form', $requisitionForm)
                                <button type="submit" x-show="isEditing" class="btn btn-sm btn-primary"
                                    x-bind:class="isEditing ? '' : 'd-none'"> <i class="fa-solid fa-save me-1"></i> Save
                                </button>

                                <button type="button" @click="isEditing = !isEditing"
                                    x-bind:class="isEditing ? 'btn-danger' : 'btn-success'" class="btn btn-sm">
                                    <i x-bind:class="isEditing ? 'fa-solid fa-xmark' : 'fa-solid fa-pen-to-square'"
                                        class="me-1_5"></i>
                                    <span x-text="isEditing ? 'Cancel' : 'Edit'"></span>
                                </button>
                            @endcan
                        @elseif($requisitionForm->status === \App\RequestFormStatus::SENT_TO_HOD)
                            @if (Auth::user()->id == $requisitionForm->head_of_department_id)
                                {{-- Approve and Decline Buttons for HOD --}}
                                <button wire:loading.attr="disabled" data-bs-toggle="modal"
                                    data-bs-target="#approveRequisitionFormHOD" type="button"
                                    class="btn btn-sm btn-success">
                                    <i class="ri-checkbox-circle-line me-1"></i> Accept
                                </button>
                                <button data-bs-toggle="modal" data-bs-target="#declineRequisitionForm" type="button"
                                    class="btn btn-sm btn-danger"> <i class="ri-close-circle-line me-1"></i>
                                    Decline
                                </button>
                            @endif
                        @elseif (
                            $requisitionForm->status === \App\RequestFormStatus::SENT_TO_PS ||
                                $requisitionForm->status === \App\RequestFormStatus::SENT_TO_DPS ||
                                $requisitionForm->status === \App\RequestFormStatus::SENT_TO_CMO)
                            @if (
                                (Auth::user()->reporting_officer_role == 'Permanent Secretary' &&
                                    $requisitionForm->status === \App\RequestFormStatus::SENT_TO_PS) ||
                                    (Auth::user()->reporting_officer_role == 'Deputy Permanent Secretary' &&
                                        $requisitionForm->status === \App\RequestFormStatus::SENT_TO_DPS) ||
                                    (Auth::user()->reporting_officer_role == 'Chief Medical Officer' &&
                                        $requisitionForm->status === \App\RequestFormStatus::SENT_TO_CMO))
                                {{-- Approve and Decline Buttons for Reporting Officer --}}

                                <button type="button"
                                    wire:confirm="Are you sure you want to confirm non-objection and send to procurement?"
                                    wire:loading.attr="disabled" wire:target="approveRequisitionReportingOfficer"
                                    wire:click="approveRequisitionReportingOfficer" class="btn btn-sm btn-success">
                                    <span wire:loading.remove>
                                        <i class="ri-checkbox-circle-line me-1"></i> Accept
                                    </span>
                                    <span wire:loading>
                                        <i class="ri-loader-2-line ri-spin me-1"></i>
                                    </span>
                                </button>
                                <button data-bs-toggle="modal" data-bs-target="#declineRequisitionForm" type="button"
                                    class="btn btn-sm btn-danger"> <i class="ri-close-circle-line me-1"></i>
                                    Decline
                                </button>
                            @endif
                        @elseif (
                            $requisitionForm->status === \App\RequestFormStatus::SENT_TO_PROCUREMENT &&
                                Auth::user()->department->name == 'Procurement Unit')
                            <a href="{{ route('requisitions.create', ['form' => $requisitionForm]) }}"
                                class="btn btn-sm btn-success">
                                <i class="fa-solid fa-plus-circle me-1"></i> Create Requisition
                            </a>
                            <button data-bs-toggle="modal" data-bs-target="#declineRequisitionForm" type="button"
                                class="btn btn-sm btn-danger"> <i class="ri-close-circle-line me-1"></i>
                                Decline
                            </button>
                        @elseif ($requisitionForm->status === \App\RequestFormStatus::COMPLETED)
                            <a href="{{ route('requisitions.view', ['id' => $requisitionForm->requisition->id]) }}"
                                target="_blank" class="btn btn-sm btn-success">
                                <i class="fa-solid fa-eye me-1"></i> View Requisition
                            </a>
                        @endif
                    </div>
                </div>


                @if (
                    $requisitionForm->status === \App\RequestFormStatus::SENT_TO_COST_BUDGETING &&
                        Auth::user()->department->name == 'Cost & Budgeting')
                    <div>
                        <div class="divider mt-6">
                            <div class="divider-text fs-5"><i class="fa-solid fa-money-check-dollar me-2"></i>Cost
                                &
                                Budgeting</div>
                        </div>

                        <div class="row mt-6">
                            {{-- LEFT COLUMN: Availability of Funds Checkbox --}}
                            <div class="col-md-6">
                                <div class="d-flex flex-column gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="defaultCheck1" wire:model.live="availability_of_funds">
                                        <label class="form-check-label" for="defaultCheck1" style="color:black">
                                            Availability of Funds
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT COLUMN: Vote Number(s) Select2 --}}
                            <div wire:ignore class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="vote_no_input" class="col-md-4 col-form-label">Vote Number(s)</label>
                                    <div class="col-md-8">
                                        {{-- Select2 element --}}
                                        <select style="width: 100%;" id="voteSelect"
                                            class="js-example-basic-multiple form-control" multiple="multiple">

                                            @foreach ($votes as $vote)
                                                {{-- Ensure selected votes are pre-selected if available --}}
                                                <option value="{{ $vote->id }}"
                                                    @if (in_array($vote->id, $selected_votes ?? [])) selected @endif>
                                                    {{ $vote->number }} | {{ $vote->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- LEFT COLUMN: Verified by Accounts Checkbox --}}
                            <div class="col-md-6">
                                <div class="d-flex flex-column gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="defaultCheck2" wire:model.live="verified_by_accounts">
                                        <label class="form-check-label" for="defaultCheck2" style="color:black">
                                            Verified by Accounts
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="cab_note_input" class="col-md-4 col-form-label">Note</label>
                                    <div class="col-md-8">
                                        <input autocomplete="off" wire:model="cab_note" type="text"
                                            class="form-control @error('cab_note')is-invalid @enderror"
                                            id="cab_note_input" placeholder="" />
                                        @error('cab_note')
                                            <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-8">
                            <div class="col d-flex justify-content-center">
                                <button type="button" wire:click="approvedByCAB" wire:loading.attr="disabled"
                                    wire:target="approvedByCAB"
                                    wire:confirm="Are you sure you want to approve this requisition form?"
                                    @disabled(!($availability_of_funds && $verified_by_accounts && count($selected_votes) > 0))
                                    class="btn btn-success waves-effect waves-light d-flex align-items-center justify-content-center gap-2 mb-5">

                                    {{-- Normal state icon + text --}}
                                    <span wire:loading.class="d-none" wire:target="approvedByCAB"
                                        class="d-flex align-items-center gap-2">
                                        <i class="tf-icons ri-send-plane-2-line me-1_5"></i>
                                        <span>Send to Department</span>
                                    </span>

                                    {{-- Loading state spinner + text --}}
                                    <span wire:loading.class.remove="d-none" wire:target="approvedByCAB"
                                        class="d-none d-flex align-items-center gap-2">
                                        <div class="spinner-border spinner-border-sm text-light" role="status"
                                            aria-hidden="true"></div>
                                        <span>Sending...</span>
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="mt-8">
                            {{-- Divider with the title Requisition Details --}}
                            <div class="divider mt-6">
                                <div class="divider-text fw-bold fs-5"><i
                                        class="ri-file-text-line me-2"></i>Requisition Form Details
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($requisitionForm->status === \App\RequestFormStatus::DENIED_BY_HOD && $requisitionForm->hod_reason_for_denial)
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <div class="text-center">
                            <strong>Requisition Declined!</strong>
                        </div>
                        <ul class="mt-1">{{ $requisitionForm->hod_reason_for_denial }}</ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @elseif (
                    $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_PS ||
                        $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_DPS ||
                        $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_CMO)
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <div class="text-center">
                            <strong>Requisition Declined by
                                {{ $requisitionForm->reportingOfficer->reporting_officer_role }}</strong>
                        </div>
                        <ul class="mt-1">{{ $requisitionForm->reporting_officer_reason_for_denial }}</ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @elseif (
                    $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_PROCUREMENT &&
                        $requisitionForm->procurement_reason_for_denial)
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <div class="text-center">
                            <strong>Requisition Declined!</strong>
                        </div>
                        <ul class="mt-1">{{ $requisitionForm->procurement_reason_for_denial }}</ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                    {{-- @elseif (
                    $requisitionForm->forwarding_minute &&
                        ($requisitionForm->status === \App\RequestFormStatus::SENT_TO_PS ||
                            $requisitionForm->status === \App\RequestFormStatus::SENT_TO_DPS ||
                            $requisitionForm->status === \App\RequestFormStatus::SENT_TO_CMO))
                    <div class="alert alert-info alert-dismissible" role="alert">
                        <div class="text-center">
                            <strong>Forwarding Minute</strong>
                        </div>
                        <ul class="mt-1">{{ $requisitionForm->forwarding_minute }}</ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div> --}}
                @endif
                <div class="row mt-6">
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="requesting_unit_label" class="col-md-4 col-form-label">Requesting
                                Unit</label>
                            <div class="col-md-8">
                                <select required wire:model="requesting_unit" disabled
                                    class="form-select @error('requesting_unit')is-invalid @enderror"
                                    id="requesting_unit_label" aria-label="Requesting Unit Select">
                                    <option value="">Select a Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                @error('requesting_unit')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="head_of_department_label" class="col-md-4 col-form-label">Head of
                                Department</label>
                            <div class="col-md-8">
                                <select required wire:model="head_of_department" disabled
                                    class="form-select @error('head_of_department')is-invalid @enderror"
                                    id="head_of_department_label" aria-label="Head of Department Select">
                                    <option value="">Select a User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('head_of_department')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-6">
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="contact_person_id_label" class="col-md-4 col-form-label">Contact
                                Person</label>
                            <div class="col-md-8">
                                <select required wire:model="contact_person_id" disabled
                                    class="form-select @error('contact_person_id')is-invalid @enderror"
                                    id="contact_person_id_label" aria-label="Contact Person Select">
                                    <option value="">Select a User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('contact_person_id')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="contact_info_input" class="col-md-4 col-form-label">Contact Person
                                Info</label>
                            <div class="col-md-8">
                                <input autocomplete="off" wire:model="contact_info" type="text"
                                    x-bind:disabled="!isEditing"
                                    class="form-control @error('contact_info')is-invalid @enderror"
                                    id="contact_info_input" placeholder="Contact Person Info"
                                    aria-describedby="contact_info_help" />
                                @error('contact_info')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="date_input" class="col-md-4 col-form-label">Date Created</label>
                            <div class="col-md-8">
                                <input autocomplete="off" wire:model="date" type="date" disabled
                                    class="form-control @error('date')is-invalid @enderror" id="date_input"
                                    aria-describedby="date_input_help" />
                                @error('date')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="row mt-6">
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="date_input" class="col-md-4 col-form-label">Date Created</label>
                            <div class="col-md-8">
                                <input autocomplete="off" wire:model="date" type="date" disabled
                                    class="form-control @error('date')is-invalid @enderror" id="date_input"
                                    aria-describedby="date_input_help" />
                                @error('date')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                    </div>
                </div> --}}

                <div class="divider mt-6">
                    <div class="divider-text fw-bold fs-5"><i class="ri-file-text-line me-2"></i>Procurement
                        Request
                    </div>
                </div>

                <p class="mt-6 fw-medium text-center">Please ensure this form is submitted with a covering memo
                    explaining the
                    request <span class="text-danger">*</span></p>

                {{-- Category --}}
                <div class="row mt-6">
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="category_input" class="col-md-4 col-form-label">Category</label>
                            <div class="col-md-8">
                                <select x-bind:disabled="!isEditing" required wire:model="category"
                                    class="form-select @error('category')is-invalid @enderror" id="category_label"
                                    aria-label="Category Select">
                                    <option value="">Select a Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                    </div>
                </div>

                <div class="row mt-6">
                    <div class="col-md-12">
                        <div class="mb-3 row">
                            <label for="justification_textarea" class="col-md-2 col-form-label">Justification for
                                Request <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <textarea wire:model="justification" x-bind:disabled="!isEditing"
                                    class="form-control @error('justification')is-invalid @enderror" id="justification_textarea" rows="4"></textarea>
                                @error('justification')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-6">
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="location_of_delivery_input" class="col-md-4 col-form-label">Location of
                                Delivery/ Installation/ Works</label>
                            <div class="col-md-8">
                                <input autocomplete="off" wire:model="location_of_delivery" type="text"
                                    x-bind:disabled="!isEditing"
                                    class="form-control @error('location_of_delivery')is-invalid @enderror"
                                    id="location_of_delivery_input" placeholder="Location of Delivery"
                                    aria-describedby="location_of_delivery_help" />
                                @error('location_of_delivery')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="date_required_by_input" class="col-md-4 col-form-label">Date Required
                                By</label>
                            <div class="col-md-8">
                                <input autocomplete="off" wire:model="date_required_by" type="date"
                                    x-bind:disabled="!isEditing"
                                    class="form-control @error('date_required_by')is-invalid @enderror"
                                    id="date_required_by_input" aria-describedby="date_required_by_help" />
                                @error('date_required_by')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-6">
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="estimated_value_input" class="col-md-4 col-form-label">Estimated
                                Value</label>
                            <div class="col-md-8">
                                <input autocomplete="off" wire:model="estimated_value" type="number" step="0.01"
                                    x-bind:disabled="!isEditing || {{ $requisitionForm->sent_to_cab ? 'true' : 'false' }}"
                                    class="form-control @error('estimated_value')is-invalid @enderror"
                                    id="estimated_value_input" placeholder="Estimated Value"
                                    aria-describedby="estimated_value_help" />
                                @error('estimated_value')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                    </div>
                </div>

                <div class="divider mt-6">
                    <div class="divider-text fw-bold fs-5"><i class="ri-list-ordered me-2"></i>Items</div>
                </div>

                @if (!$requisitionForm->hod_approval)
                    <div class="row">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#addItemModal"
                            x-bind:class="{ 'pointer-events-none opacity-50': !isEditing }" x-bind:disabled="!isEditing"
                            class="btn btn-primary waves-effect waves-light w-25 m-auto">
                            <span class="fa-solid fa-file-circle-plus me-1_5"></span>Add Item
                        </button>
                    </div>
                @endif

                <p class="mt-6 fw-medium text-center">For items with multiple specifications, please attach
                    additional
                    documentation
                    as necessary <span class="text-danger">*</span></p>

                <div class="row mt-6" x-data="{
                    items: $wire.entangle('items'),
                }">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty. In Stock</th>
                                    <th>Qty. Requesting</th>
                                    <th>Unit of Measure</th>
                                    <th>Size</th>
                                    <th>Colour</th>
                                    <th>Brand/Model</th>
                                    <th>Other</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($items as $key => $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['qty_in_stock'] }}</td>
                                        <td>{{ $item['qty_requesting'] }}</td>
                                        <td>{{ $item['unit_of_measure'] }}</td>
                                        <td>{{ $item['size'] }}</td>
                                        <td>{{ $item['colour'] }}</td>
                                        <td>{{ $item['brand_model'] }}</td>
                                        <td>{{ $item['other'] }}</td>
                                        <td>
                                            <button type="button" x-bind:disabled="!isEditing"
                                                class="btn btn-dark mx-auto me-1"
                                                wire:click="displayEditModal({{ $key }})"><i
                                                    class="fa-solid fa-pen-to-square"></i></button>

                                            <button type="button" class="btn btn-sm btn-danger"
                                                wire:click="removeItem({{ $key }})"
                                                :disabled="items.length <= 1 || !isEditing"
                                                title="Cannot delete the last item">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No items added.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="divider mt-6">
                    <div class="divider-text fw-bold fs-5"><i class="fa-solid fa-money-check-dollar me-2"></i>Cost
                        &
                        Budgeting</div>
                </div>

                @if (!$requisitionForm->sent_to_cab)
                    <div class="col d-flex justify-content-center">
                        <button type="button" wire:click="sendToCAB" wire:loading.attr="disabled"
                            wire:target="sendToCAB" wire:confirm="Are you sure you want to send to Cost & Budgeting?"
                            @disabled(!$requisitionForm->estimated_value || empty($items))
                            class="btn btn-info waves-effect waves-light d-flex align-items-center justify-content-center gap-2">

                            {{-- Normal state icon + text --}}
                            <span wire:loading.class="d-none" wire:target="sendToCAB"
                                class="d-flex align-items-center gap-2">
                                <i class="tf-icons ri-mail-send-line me-1_5"></i>
                                <span>Send to Cost & Budgeting</span>
                            </span>

                            {{-- Loading state spinner + text --}}
                            <span wire:loading.class.remove="d-none" wire:target="sendToCAB"
                                class="d-none d-flex align-items-center gap-2">
                                <div class="spinner-border spinner-border-sm text-light" role="status"
                                    aria-hidden="true"></div>
                                <span>Sending...</span>
                            </span>
                        </button>
                    </div>
                @elseif ($requisitionForm->status === \App\RequestFormStatus::SENT_TO_COST_BUDGETING)
                    <div class="row mt-4">
                        <div class="alert alert-warning text-center" role="alert">
                            <strong>This requisition form is pending approval from Cost & Budgeting.</strong>
                        </div>
                    </div>
                @else
                    <div class="row mt-6">
                        {{-- LEFT COLUMN: Availability of Funds Checkbox --}}
                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="defaultCheck1" disabled wire:model="availability_of_funds">
                                    <label class="form-check-label fw-bold" for="defaultCheck1" style="color:black">
                                        Availability of Funds
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: Vote Number(s) Select2 --}}
                        <div wire:ignore class="col-md-6">
                            <div class="mb-3 row">
                                <label for="vote_no_input" class="col-md-4 col-form-label">Vote Number(s)</label>
                                <div class="col-md-8">
                                    {{-- Select2 element --}}
                                    <select disabled style="width: 100%;" id="voteSelect"
                                        class="js-example-basic-multiple form-control" multiple="multiple">

                                        @foreach ($votes as $vote)
                                            {{-- Ensure selected votes are pre-selected if available --}}
                                            <option value="{{ $vote->id }}"
                                                @if (in_array($vote->id, $selected_votes ?? [])) selected @endif>
                                                {{ $vote->number }} | {{ $vote->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- LEFT COLUMN: Verified by Accounts Checkbox --}}
                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="defaultCheck2" disabled wire:model="verified_by_accounts">
                                    <label class="form-check-label fw-bold" for="defaultCheck2" style="color:black">
                                        Verified by Accounts
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: Note (cab_note) Textarea --}}
                        @if ($requisitionForm->cab_note)
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label for="cab_note_input" class="col-md-4 col-form-label">Note</label>
                                    <div class="col-md-8">
                                        <input disabled autocomplete="off" wire:model="cab_note" type="text"
                                            class="form-control @error('cab_note')is-invalid @enderror"
                                            id="cab_note_input" placeholder="" />
                                        @error('cab_note')
                                            <div class="text-danger"> {{ $message }} </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="divider mt-6">
                    <div class="divider-text fw-bold fs-5"><i class="fa-solid fa-user-tie me-2"></i>Requesting
                        Head of
                        Department/Unit/Division</div>
                </div>

                @if (
                    !$requisitionForm->date_sent_to_hod ||
                        $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_HOD ||
                        $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_PS ||
                        $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_DPS ||
                        $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_CMO)
                    <div class="row mt-8">
                        <div class="text-center m-auto">
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                data-bs-target="#sendToHodModal" @disabled($this->sendToHodDisabled)>
                                <i class="tf-icons ri-mail-send-line me-1_5"></i>
                                <span>Send to Head of Department</span>
                            </button>
                        </div>
                    </div>
                @elseif($requisitionForm->status === \App\RequestFormStatus::SENT_TO_HOD)
                    <div class="row mt-4">
                        <div class="alert alert-warning text-center" role="alert">
                            <strong>This requisition form is pending approval from
                                {{ $requisitionForm->headOfDepartment->name ?? 'the Head of Department' }}.</strong>
                        </div>
                    </div>
                @elseif ($requisitionForm->hod_approval)
                    <div class="row mt-4">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th style="width: 33%;">Name</th>
                                    <th style="width: 33%;">Signature</th>
                                    <th style="width: 33%;">Date Approved</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        @if ($requisitionForm->headOfDepartment)
                                            {{ $requisitionForm->headOfDepartment->name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($requisitionForm->headOfDepartment)
                                            {{ $requisitionForm->headOfDepartment->initials }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($requisitionForm->headOfDepartment)
                                            {{ $requisitionForm->hod_approval_date->format('d/m/Y H:i:s') }}
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($requisitionForm->status === \App\RequestFormStatus::DENIED_BY_PROCUREMENT)
                    <div class="row mt-8" x-show="!isEditing">
                        <div class="text-center m-auto">
                            <button type="button" class="btn btn-sm btn-info" wire:loading.attr="disabled"
                                wire:target="sendToProcurement"
                                wire:confirm="Are you sure you want to send this form to Procurement for approval?"
                                wire:click="sendToProcurement">
                                <i class="tf-icons ri-mail-send-line me-1_5"></i>
                                <span>Send to Procurement</span>
                                <div wire:loading wire:target="sendToProcurement"
                                    class="spinner-border spinner-border-sm text-secondary mx-1" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </button>
                        </div>
                    </div>
                @endif

                @if ($requisitionForm->status === \App\RequestFormStatus::SENT_TO_PS)
                    <div class="row mt-4">
                        <div class="alert alert-warning text-center" role="alert">
                            <strong>This requisition form is pending approval from the Permanent Secretary.</strong>
                        </div>
                    </div>
                @elseif($requisitionForm->status === \App\RequestFormStatus::SENT_TO_PS)
                    <div class="row mt-4">
                        <div class="alert alert-warning text-center" role="alert">
                            <strong>This requisition form is pending approval from the Deputy Permanent
                                Secretary.</strong>
                        </div>
                    </div>
                @elseif($requisitionForm->status === \App\RequestFormStatus::SENT_TO_CMO)
                    <div class="row mt-4">
                        <div class="alert alert-warning text-center" role="alert">
                            <strong>This requisition form is pending approval from the Chief Medical
                                Officer.</strong>
                        </div>
                    </div>
                @endif

                @if (
                    $requisitionForm->reporting_officer_approval ||
                        $requisitionForm->second_reporting_officer_approval ||
                        $requisitionForm->third_reporting_officer_approval)
                    <div class="divider mt-6">
                        <div class="divider-text fw-bold fs-5"><i class="fa-solid fa-user-tie me-2"></i>
                            Non-Objection Required From</div>
                        <div class="row mt-4">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Name</th>
                                        <th>Signature</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($requisitionForm->reporting_officer_approval && $requisitionForm->reportingOfficer)
                                        <tr>
                                            <td>{{ $requisitionForm->reportingOfficer->reporting_officer_role ?? 'Reporting Officer' }}
                                            </td>
                                            <td>{{ $requisitionForm->reportingOfficer->name }}</td>
                                            <td>{{ $requisitionForm->reportingOfficer->initials }}</td>
                                            <td>{{ $requisitionForm->reporting_officer_approval_date ? $requisitionForm->reporting_officer_approval_date->format('d/m/Y H:i:s') : '' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($requisitionForm->second_reporting_officer_approval && $requisitionForm->secondReportingOfficer)
                                        <tr>
                                            <td>{{ $requisitionForm->secondReportingOfficer->reporting_officer_role ?? 'Reporting Officer' }}
                                            </td>
                                            <td>{{ $requisitionForm->secondReportingOfficer->name }}</td>
                                            <td>{{ $requisitionForm->secondReportingOfficer->initials }}</td>
                                            <td>{{ $requisitionForm->second_reporting_officer_approval_date ? $requisitionForm->second_reporting_officer_approval_date->format('d/m/Y H:i:s') : '' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($requisitionForm->third_reporting_officer_approval && $requisitionForm->thirdReportingOfficer)
                                        <tr>
                                            <td>{{ $requisitionForm->thirdReportingOfficer->reporting_officer_role ?? 'Reporting Officer' }}
                                            </td>
                                            <td>{{ $requisitionForm->thirdReportingOfficer->name }}</td>
                                            <td>{{ $requisitionForm->thirdReportingOfficer->initials }}</td>
                                            <td>{{ $requisitionForm->third_reporting_officer_approval_date ? $requisitionForm->third_reporting_officer_approval_date->format('d/m/Y H:i:s') : '' }}
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($requisitionForm->requisition)
                    <div class="divider mt-6">
                        <div class="divider-text fw-bold fs-5"><i class="fa-solid fa-dollar-sign me-2"></i>Procurement
                        </div>
                    </div>

                    <div class="row mt-6">
                        <div class="col">
                            <label for="requesting_unit_label" class="col col-form-label"><span class="fw-bold">Date
                                    Received:
                                </span>{{ $requisitionForm->reporting_officer_approval_date ? \Carbon\Carbon::parse($requisitionForm->reporting_officer_approval_date)->format('d/m/Y') : '' }}</label>
                        </div>

                        <div class="col">
                            <label for="requesting_unit_label" class="col col-form-label"><span class="fw-bold">Seen
                                    By:
                                </span>{{ Str::of($requisitionForm->requisition?->created_by ?? '')->replace('.', ' ')->title() }}</label>
                        </div>
                    </div>

                    <div class="row mt-6">
                        <div class="col">
                            <label for="requesting_unit_label" class="col col-form-label"><span
                                    class="fw-bold">Procurement Officer Assigned:
                                </span>{{ $requisitionForm->requisition?->procurement_officer?->name ?? '' }}</label>
                        </div>

                        <div class="col">
                            <label for="requesting_unit_label" class="col col-form-label"><span
                                    class="fw-bold">Expected Date of Completion
                                    By:
                                </span></label>
                        </div>
                    </div>
                @endif

                <div class="divider">
                    <div class="divider-text fw-bold fs-5 mt-4"><i class="fa-solid fa-file-arrow-up me-2"></i>File
                        Uploads
                    </div>
                </div>

                <div class="row">
                    <div class="col" style="text-align: center;padding-bottom:10px">
                        @error('uploads')
                            <div class="text-danger fw-bold"> {{ $message }} </div>
                        @enderror

                        <input wire:model="uploads" type="file" multiple class="form-control"
                            style="display: inline;width: 400px;height:45px">
                        <button @disabled(!$this->uploads) wire:click.prevent="uploadFiles()"
                            class="btn btn-primary" wire:loading.attr="disabled" wire:target="uploads,uploadFiles"
                            style="width: 8rem"><i class="fas fa-plus me-2"></i> Upload</button>
                        <div wire:loading wire:target="uploads,uploadFiles"
                            class="spinner-border spinner-border-sm text-secondary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

                <div class="row mt-6">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">File Name</th>
                                        <th style="width: 30%;">Uploaded By</th>
                                        <th style="width: 10%; text-align: center;">Date</th>
                                        {{-- HIDE HEADER IF NOT EDITING --}}
                                        <th x-show="isEditing" style="width: 10%; text-align: center;">Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse($requisitionForm->uploads as $upload)
                                        <tr>
                                            <td>
                                                <a href="{{ Storage::url($upload->file_path) }}" target="_blank">
                                                    {{ $upload->file_name }} <i
                                                        class="fa-solid fa-arrow-up-right-from-square"></i>
                                                </a>
                                            </td>
                                            <td>{{ $upload->uploaded_by ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $upload->created_at->format('d/m/Y') }}
                                            </td>
                                            {{-- HIDE DATA CELL IF NOT EDITING --}}
                                            <td x-show="isEditing" class="text-center">
                                                @can('edit-records')
                                                    <a href="javascript:void(0)"
                                                        wire:confirm="Are you sure you want to delete this file?"
                                                        wire:click="deleteFile({{ $upload->id }})" class="text-danger">
                                                        <i class="ri-delete-bin-2-line"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No files uploaded.</td>
                                            {{-- Add a hidden column cell for layout when no files exist --}}
                                            <td x-show="isEditing" class="text-center"></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="divider">
                    <div class="divider-text fw-bold fs-5 mt-4"><i class="fa-solid fa-file-pen me-2"></i>Logs
                    </div>
                </div>

                <div class="row">
                    <div class="text-center m-auto">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#addLogModal"
                            class="btn btn-sm btn-primary">
                            <i class="tf-icons ri-file-add-line me-1_5"></i>
                            <span>Add Log</span>
                        </button>
                    </div>
                </div>

                <div class="row mt-6">
                    <table class="table table-hover table-bordered w-100">
                        <thead>
                            <tr>
                                <th>Details</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($this->sortedLogs as $log)
                                <tr>
                                    <td>{{ $log->details }}</td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No Logs added.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- <div class="divider">
                    <div class="divider-text fw-bold fs-5">Head of Department Approval</div>
                </div>

                <div class="row mt-8">
                    <div class="text-center">
                        <button data-bs-toggle="modal" data-bs-target="#acceptRequisitionForm"
                            x-bind:disabled="!isEditing" class="btn btn-success waves-effect waves-light"
                            style="width: 100px">
                            <span class="tf-icons ri-checkbox-circle-line me-1_5"></span>Accept
                        </button>
                        <button class="btn btn-danger waves-effect waves-light" data-bs-toggle="modal"
                            x-bind:disabled="!isEditing" data-bs-target="#declineRequisitionForm"
                            style="width: 100px">
                            <span class="tf-icons ri-close-circle-line me-1_5"></span>Decline
                        </button>
                    </div>
                </div> --}}

                {{-- Only show Save button when editing is enabled --}}
                @if (
                    !$requisitionForm->date_sent_to_hod ||
                        $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_HOD ||
                        $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_PS ||
                        $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_DPS ||
                        $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_CMO ||
                        $requisitionForm->status === \App\RequestFormStatus::DENIED_BY_PROCUREMENT)
                    <div class="row mt-8" x-show="isEditing">
                        <div class="col"></div>
                        <div class="col text-center m-auto">
                            @can('edit-requisition-form', $requisitionForm)
                                <button type="submit" wire:loading.attr="disabled"
                                    wire:target="save,uploads,justification"
                                    class="btn btn-primary waves-effect waves-light" style="width: 100px">
                                    <span class="tf-icons ri-save-3-line me-1_5"></span>Save
                                </button>
                                &nbsp;
                                <button type="button" @click="isEditing = false"
                                    class="btn btn-dark waves-effect waves-light" style="width: 100px">
                                    <span class="tf-icons ri-close-circle-line me-1_5"></span>Cancel
                                </button>
                            @endcan
                        </div>
                        <div class="col">
                            <a class="btn btn-dark text-end" style="float: right;"
                                href="{{ route('requisition_forms.preview', ['id' => $requisitionForm->id]) }}"
                                target="_blank">
                                <i class="fa-solid fa-file-lines me-2"></i>View Form
                            </a>
                        </div>
                    </div>
                    <div class="row mt-8" x-show="!isEditing">
                        <div class="col"></div>
                        <div class="col">
                            <div class="text-center m-auto">
                                @can('edit-requisition-form', $requisitionForm)
                                    <button type="button" @click="isEditing = !isEditing"
                                        x-bind:class="isEditing ? 'btn-danger' : 'btn-success'" class="btn btn-sm">
                                        <i class="fa-solid fa-pen-to-square me-1_5"></i>
                                        <span x-text="isEditing ? 'Cancel' : 'Edit'"></span>
                                    </button>
                                @endcan
                            </div>
                        </div>
                        <div class="col">
                            <a class="btn btn-dark text-end" style="float: right;"
                                href="{{ route('requisition_forms.preview', ['id' => $requisitionForm->id]) }}"
                                target="_blank">
                                <i class="fa-solid fa-file-lines me-2"></i>View Form
                            </a>
                        </div>
                    </div>
                @else
                    {{-- Display View form button on far right --}}
                    <div class="row mt-8">
                        <div class="col">
                            <a class="btn btn-dark text-end" style="float: right;"
                                href="{{ route('requisition_forms.preview', ['id' => $requisitionForm->id]) }}"
                                target="_blank">
                                <i class="fa-solid fa-file-lines me-2"></i>View Form
                            </a>
                        </div>
                    </div>
                @endif
            </form>

        </div>
    </div>
</div>

@script
    <script>
        $(document).ready(function() {
            window.addEventListener('close-add-item-modal', event => {
                $('#addItemModal').modal('hide');
            });

            window.addEventListener('display-edit-item-modal', event => {
                $('#editItemModal').modal('show');
            })

            window.addEventListener('close-edit-item-modal', event => {
                $('#editItemModal').modal('hide');
            })

            window.addEventListener('close-log-modal', event => {
                $('#addLogModal').modal('hide');
            });

            window.addEventListener('close-ro-approval-modal', event => {
                $('#approveRequisitionFormReportingOfficer').modal('hide');
            });

            window.addEventListener('close-hod-approval-modal', event => {
                $('#approveRequisitionFormHOD').modal('hide');
            });

            window.addEventListener('close-decline-modal', event => {
                $('#declineRequisitionForm').modal('hide');
            });

            window.addEventListener('close-sent-to-hod-modal', event => {
                $('#sendToHodModal').modal('hide');
            });

            window.addEventListener('close-forward-form-modal', event => {
                $('#forwardFormReportingOfficer').modal('hide');
            });
        });


        $('#voteSelect').select2();
        $('#voteSelect').val(@json($this->selected_votes)).trigger('change');

        $('#voteSelect').on('change', function() {
            var selectedValues = $(this).val();
            $wire.set('selected_votes', selectedValues);
        });

        $wire.on('scrollToError', () => {
            setTimeout(() => {
                const firstErrorElement = document.querySelector('.is-invalid');
                if (firstErrorElement) {
                    firstErrorElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    firstErrorElement.focus();
                }
            }, 100);
        });
    </script>
@endscript
