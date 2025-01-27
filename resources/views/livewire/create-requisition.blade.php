<div>
    @include('add-log')
    <form wire:submit.prevent="save">
        <div class="card" x-data="{
            sent_to_cb: $wire.entangle('sent_to_cb'),
            ps_approval: $wire.entangle('ps_approval')
        }" x-cloak>
            <div class="card-body">

                <div class="d-sm-flex align-items-center justify-content-between mb-5">
                    <a href="{{ route('requisitions.index') }}" class="btn btn-primary">
                        <i class="ri-arrow-left-circle-line me-1"></i> Back
                    </a>
                    <h1 class="h3 mb-0 text-gray-800" style="flex: 1; text-align: center;">
                        <strong style="margin-right: 90px"><i class="fa-solid fa-file-circle-plus"></i> Create
                            Requisition</strong>
                    </h1>
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

                            <select class="js-example-basic-single form-control" id="unitSelect" style="width: 100%">
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

                            <select class="js-example-basic-single form-control" id="sofSelect" style="width: 100%">
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

                <div class="row">

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

                <div class="row" x-show="ps_approval == 'Approved'" x-transition>

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="vendor_name" type="text"
                                class="form-control @error('vendor_name')is-invalid @enderror" id="floatingInput"
                                placeholder="ex. Fujitsu" aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Vendor Name</label>
                        </div>
                        @error('vendor_name')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="amount" type="number"
                                class="form-control @error('amount')is-invalid @enderror" id="floatingInput"
                                placeholder="0.00" aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Amount</label>
                        </div>
                        @error('amount')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
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
