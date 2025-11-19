<div>
    @include('add-log')
    <form wire:submit.prevent="save">
        <div class="card" x-data="{
            sent_to_cb: $wire.entangle('sent_to_cb'),
            ps_approval: $wire.entangle('ps_approval')
        }" x-cloak>
            <div class="card-body">

                <div class="d-flex align-items-center justify-content-between mb-5 position-relative">
                    {{-- Left Button --}}
                    <div>
                        <a href="{{ route('requisitions.index') }}" class="btn btn-primary">
                            <i class="ri-arrow-left-circle-line me-1"></i> Back
                        </a>
                    </div>

                    {{-- Center Title --}}
                    <div class="position-absolute start-50 translate-middle-x text-center">
                        <h1 class="h3 mb-0 text-gray-800">
                            <strong><i class="fa-solid fa-file-circle-plus me-2"></i>Create Requisition</strong>
                        </h1>
                    </div>

                    {{-- Right Button --}}
                    <div>
                        <a href="{{ route('requisition_forms.view', ['id' => $form->id]) }}" target="_blank"
                            class="btn btn-success btn-sm">
                            <i class="fa-solid fa-plus-circle me-1"></i> View Requisition Form
                        </a>
                    </div>
                </div>




                <div class="row mt-8">

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="requisition_no" type="text"
                                class="form-control @error('requisition_no')is-invalid @enderror" id="floatingInput"
                                placeholder="Requisition Number" aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Requisition Number</label>
                        </div>
                        @error('requisition_no')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div wire:ignore>
                            <label style="width:100%" for="unitSelect">Requesting Unit:</label>

                            <select wire:model="requesting_unit" class="js-example-basic-single form-control"
                                id="unitSelect" style="width: 100%">
                                <option value="" selected>Select a Unit</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
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
                                class="form-control @error('file_no')is-invalid @enderror" id="floatingInput"
                                placeholder="File Number / Form" aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">File Number / Form</label>
                        </div>
                        @error('file_no')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="item" type="text"
                                class="form-control @error('item')is-invalid @enderror" id="floatingInput"
                                placeholder="ex. SSL Certificate" aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Item</label>
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

                            <select wire:model="source_of_funds" class="js-example-basic-single form-control"
                                id="sofSelect" style="width: 100%">
                                <option value="" selected>Select a Vote</option>
                                @foreach ($votes as $vote)
                                    <option value="{{ $vote->number }}">{{ $vote->number }}</option>
                                @endforeach

                            </select>
                        </div>
                        @error('source_of_funds')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="date_received_procurement" type="date"
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
                                id="exampleFormControlSelect1" aria-label="Default select example">
                                <option value="" selected>Select Employee</option>
                                @foreach ($staff as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }}</option>
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
                                class="form-control @error('date_assigned')is-invalid @enderror" id="floatingInput"
                                aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Date Assigned to Officer</label>
                        </div>
                        @error('date_assigned')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>

                {{-- <div class="row">

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="actual_cost" type="number" step="0.01"
                                class="form-control @error('actual_cost')is-invalid @enderror" id="actualCostInput"
                                placeholder="Enter Actual Cost" />
                            <label for="actualCostInput">Actual Cost</label>
                        </div>
                        @error('actual_cost')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <div wire:ignore>
                            <label style="width:100%" for="fundingSelect">Funding Availability:</label>
                            <select wire:model="funding_availability" class="js-example-basic-single form-control"
                                id="fundingSelect" style="width: 100%">
                                <option value="" selected>Select a Vote</option>
                                @foreach ($votes as $vote)
                                    <option value="{{ $vote->number }}">{{ $vote->number }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('funding_availability')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div> --}}

                <div class="row mt-6">

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="date_sent_aov_procurement" type="date"
                                class="form-control @error('date_sent_aov_procurement')is-invalid @enderror"
                                id="dateSentAOVInput" placeholder="Date Sent to AOV Procurement" />
                            <label for="dateSentAOVInput">Date Sent to AOV Procurement</label>
                        </div>
                        @error('date_sent_aov_procurement')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div x-data="{
                    siteVisit: $wire.entangle('site_visit'),
                    noteToPs: $wire.entangle('note_to_ps')
                }">

                    {{-- 🔹 Row 1: Site Visit --}}
                    <div class="row mt-4 align-items-center">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="siteVisitCheck"
                                    x-model="siteVisit">
                                <label class="form-check-label" for="siteVisitCheck">
                                    Site Visit Required
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6" x-show="siteVisit" x-transition>
                            <div class="form-floating form-floating-outline">
                                <input autocomplete="off" wire:model="site_visit_date" type="date"
                                    class="form-control @error('site_visit_date')is-invalid @enderror"
                                    id="siteVisitDateInput" placeholder="Site Visit Date" />
                                <label for="siteVisitDateInput">Site Visit Date</label>
                            </div>
                            @error('site_visit_date')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>

                    {{-- 🔹 Row 2: Note to PS --}}
                    <div class="row mt-4 align-items-center" x-show="siteVisit" x-transition>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="noteToPsCheck"
                                    x-model="noteToPs">
                                <label class="form-check-label" for="noteToPsCheck">
                                    Note to PS
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6" x-show="noteToPs" x-transition>
                            <div class="form-floating form-floating-outline">
                                <input autocomplete="off" wire:model="note_to_ps_date" type="date"
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
                            <input autocomplete="off" wire:model="tender_issue_date" type="date"
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
                            <input autocomplete="off" wire:model="tender_deadline_date" type="date"
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
                            <input autocomplete="off" wire:model="evaluation_start_date" type="date"
                                class="form-control @error('evaluation_start_date')is-invalid @enderror"
                                id="evaluationStartDateInput" placeholder="Evaluation Start Date" />
                            <label for="evaluationStartDateInput">Evaluation Start Date</label>
                        </div>
                        @error('evaluation_start_date')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="evaluation_end_date" type="date"
                                class="form-control @error('evaluation_end_date')is-invalid @enderror"
                                id="evaluationEndDateInput" placeholder="Evaluation End Date" />
                            <label for="evaluationEndDateInput">Evaluation End Date</label>
                        </div>
                        @error('evaluation_end_date')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-6">

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="date_sent_dps" type="date"
                                class="form-control @error('date_sent_dps')is-invalid @enderror" id="floatingInput"
                                aria-describedby="floatingInputHelp" />
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
                                id="exampleFormControlSelect1" aria-label="Default select example">
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

                {{-- NEW PS Approval Date Field Start --}}
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
                {{-- NEW PS Approval Date Field End --}}


                {{-- Vendors Section --}}
                <div class="row mt-2" x-show="ps_approval == 'Approved'" x-transition
                     x-data="{ vendors: $wire.entangle('vendors') }">

                    <h4 class="text-center fw-bold">Vendors</h4>

                    <template x-if="vendors.length === 0">
                        <p class="text-center">Click the button below to add a vendor</p>
                    </template>

                    <template x-for="(vendor, index) in vendors" :key="index">
                        <div class="row mx-auto mt-2">
                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" x-model="vendor.vendor_name"
                                        type="text"
                                        :class="{ 'is-invalid': $wire.errors['vendors.' + index + '.vendor_name'] }"
                                        class="form-control" />
                                    <label>Vendor Name</label>
                                </div>
                                <template x-if="$wire.errors['vendors.' + index + '.vendor_name']">
                                    <div class="text-danger" x-text="$wire.errors['vendors.' + index + '.vendor_name']"></div>
                                </template>
                            </div>
                             <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" x-model="vendor.vendor_items"
                                        type="text"
                                        :class="{ 'is-invalid': $wire.errors['vendors.' + index + '.vendor_items'] }"
                                        class="form-control" />
                                    <label>Item(s)</label>
                                </div>
                                <template x-if="$wire.errors['vendors.' + index + '.vendor_items']">
                                    <div class="text-danger" x-text="$wire.errors['vendors.' + index + '.vendor_items']"></div>
                                </template>
                            </div>
                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" x-model="vendor.amount"
                                        type="number" step="0.01"
                                        :class="{ 'is-invalid': $wire.errors['vendors.' + index + '.amount'] }"
                                        class="form-control" />
                                    <label>Amount</label>
                                </div>
                                <template x-if="$wire.errors['vendors.' + index + '.amount']">
                                    <div class="text-danger" x-text="$wire.errors['vendors.' + index + '.amount']"></div>
                                </template>
                            </div>
                            <div class="col-1">
                                <button type="button" @click="vendors.splice(index, 1)"
                                    class="btn rounded-pill btn-icon btn-danger mx-auto mt-2">
                                    <span class="tf-icons ri-delete-bin-2-line"></span>
                                </button>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="vendors.push({ vendor_name: '', vendor_items: '', amount: '' })"
                        class="btn rounded-pill btn-icon btn-primary mx-auto mt-2">
                        <span class="tf-icons ri-add-line ri-22px"></span>
                    </button>
                </div>

                <div class="row" x-show="ps_approval == 'Approval Denied'" x-transition>

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="denied_note" type="text"
                                class="form-control @error('denied_note')is-invalid @enderror" id="floatingInput"
                                placeholder="ex. Fujitsu" aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Reason for Denial</label>
                        </div>
                        @error('denied_note')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>

                <div class="divider" style="margin-top: 40px">
                    <div class="divider-text">
                        <i class="fa-solid fa-file-pen"></i>
                    </div>
                </div>

                <div class="row">
                    <h4 class="text-center fw-bold">Status Log</h4>
                </div>


                <div class="row">
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addLogModal"
                        class="btn btn-primary waves-effect waves-light w-25 m-auto">
                        <span class="tf-icons ri-file-add-line me-1_5"></span>Add Log
                    </a>
                </div>

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
                                    <td>{{ $log }}</td>
                                    <td class="text-center">

                                        <button wire:click="removeLog({{ $index }})" type="button"
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


                <div class="divider" style="margin-top: 40px">
                    <div class="divider-text">
                        <i class="fa-solid fa-file-arrow-up fs-4"></i>
                    </div>
                </div>

                <div class="row">
                    <h4 class="text-center fw-bold">File Uploads</h4>
                </div>


                <div class="row">
                    <div class="col" style="text-align: center;padding-bottom:10px">
                        @error('uploads')
                            <div class="text-danger fw-bold"> {{ $message }} </div>
                        @enderror

                        <input wire:model="uploads" type="file" multiple class="form-control"
                            style="display: inline;width: 400px;height:45px">
                        <span wire:loading wire:target="uploads">Uploading...</span>
                    </div>
                </div>

                <div class="row mt-8">
                    <button wire:loading.attr="disabled" class="btn btn-primary waves-effect waves-light m-auto"
                        style="width: 100px">
                        <span class="tf-icons ri-save-3-line me-1_5"></span>Save
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>


@script
    <script>
        $(document).ready(function() {
            $('#unitSelect').select2();

            $('#unitSelect').on('change', function() {
                var selectedValue = $(this).val(); // Get selected values as an array
                $wire.set('requesting_unit', selectedValue); // Pass selected values to Livewire
            });

            $('#sofSelect').select2();

            $('#sofSelect').on('change', function() {
                var selectedValue = $(this).val(); // Get selected values as an array
                $wire.set('source_of_funds', selectedValue); // Pass selected values to Livewire
            });

            $('#fundingSelect').select2();
            $('#fundingSelect').on('change', function() {
                var selectedValue = $(this).val(); // Get selected values as an array
                $wire.set('funding_availability', selectedValue); // Pass selected values to Livewire
            });
        });


        window.addEventListener('close-log-modal', event => {
            $('#addLogModal').modal('hide');
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
