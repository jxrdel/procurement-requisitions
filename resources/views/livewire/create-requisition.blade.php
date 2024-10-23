<div>
    @include('add-log')
    <form wire:submit.prevent="save">
        <div class="card" x-data="{ sent_to_dfa: $wire.entangle('sent_to_dfa')  }" x-cloak>
            <div class="card-body">
                    
                <div class="d-sm-flex align-items-center justify-content-between mb-5">
                    <a href="{{route('requisitions.index')}}" class="btn btn-primary">
                        <i class="ri-arrow-left-circle-line me-1"></i> Back
                    </a>
                    <h1 class="h3 mb-0 text-gray-800" style="flex: 1; text-align: center;">
                        <strong style="margin-right: 90px"><i class="fa-solid fa-file-circle-plus"></i> Create Requisition</strong>
                    </h1>
                </div>
                
                    
                <div class="row mt-8">
        
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input
                            autocomplete="off"
                            wire:model="requisition_no"
                            type="text"
                            class="form-control @error('requisition_no')is-invalid @enderror"
                            id="floatingInput"
                            placeholder="Requisition Number"
                            aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Requisition Number</label>
                        </div>
                        @error('requisition_no')<div class="text-danger"> {{ $message }} </div>@enderror
                    </div>
                    <div class="col-md-6">
                        <div>
                            <label wire:ignore style="width:100%" for="unitSelect">Requesting Unit:
                            
                                <select class="js-example-basic-single form-control" id="unitSelect" style="width: 100%">
                                    <option value="" selected>Select a Unit</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name}}</option>
                                    @endforeach
                    
                                </select>
                            </label>
                        </div>
                        @error('requesting_unit')<div class="text-danger"> {{ $message }} </div>@enderror
                    </div>
                </div>
                    
                <div class="row mt-7">
        
                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input
                            autocomplete="off"
                            wire:model="file_number"
                            type="text"
                            class="form-control @error('file_number')is-invalid @enderror"
                            id="floatingInput"
                            placeholder="File Number"
                            aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">File Number</label>
                        </div>
                        @error('file_number')<div class="text-danger"> {{ $message }} </div>@enderror
                    </div>
        
                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input
                            autocomplete="off"
                            wire:model="item"
                            type="text"
                            class="form-control @error('item')is-invalid @enderror"
                            id="floatingInput"
                            placeholder="ex. SSL Certificate"
                            aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Item</label>
                        </div>
                        @error('item')<div class="text-danger"> {{ $message }} </div>@enderror
                    </div>
                </div>

                <div class="row mt-7">
        
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input
                            autocomplete="off"
                            wire:model="source_of_funds"
                            type="text"
                            class="form-control @error('source_of_funds')is-invalid @enderror"
                            id="floatingInput"
                            placeholder="Source of Funds"
                            aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Source of Funds</label>
                        </div>
                        @error('source_of_funds')<div class="text-danger"> {{ $message }} </div>@enderror
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline mb-6">
                        <select required wire:model="assigned_to" class="form-select @error('assigned_to')is-invalid @enderror" id="exampleFormControlSelect1" aria-label="Default select example">
                            <option value="Not Sent" selected>Select Employee</option>
                            @foreach ($staff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name}}</option>
                            @endforeach
                        </select>
                        <label for="exampleFormControlSelect1">Assigned To</label>
                        @error('assigned_to')<div class="text-danger"> {{ $message }} </div>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input
                            autocomplete="off"
                            wire:model="date_sent_ps"
                            type="date"
                            class="form-control @error('date_sent_ps')is-invalid @enderror"
                            id="floatingInput"
                            aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Date Sent to PS</label>
                        </div>
                        @error('date_sent_ps')<div class="text-danger"> {{ $message }} </div>@enderror
                    </div>

                    <div class="col">
                        <div class="form-floating form-floating-outline mb-6">
                        <select required wire:model="ps_approval" class="form-select @error('ps_approval')is-invalid @enderror" id="exampleFormControlSelect1" aria-label="Default select example">
                            <option value="Not Sent" selected>Not Sent</option>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Approval Denied">Approval Denied</option>
                        </select>
                        <label for="exampleFormControlSelect1">PS Approval</label>
                        </div>
                        @error('ps_approval')<div class="text-danger"> {{ $message }} </div>@enderror
                    </div>
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
                            @error('uploads')<div class="text-danger fw-bold"> {{ $message }} </div>@enderror
                    
                            <input wire:model="uploads" type="file" multiple class="form-control" style="display: inline;width: 400px;height:45px">
                            <span wire:loading wire:target="uploads">Uploading...</span>
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
                    <a href="javascript:void(0);"  data-bs-toggle="modal" data-bs-target="#addLogModal" class="btn btn-primary waves-effect waves-light w-25 m-auto">
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
                            <td>{{$log}}</td>
                            <td class="text-center">
                                
                                <button wire:click="removeLog({{$index}})" type="button" class="btn btn-danger">
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
                <div class="row mt-8">
                    <button wire:loading.attr="disabled" class="btn btn-primary waves-effect waves-light m-auto" style="width: 100px">
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
        // Initialize select2
        
        
        $('#unitSelect').select2();
        
        $('#unitSelect').on('change', function() {
            var selectedValue = $(this).val(); // Get selected values as an array
            $wire.set('requesting_unit', selectedValue); // Pass selected values to Livewire
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
                    firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstErrorElement.focus(); // Optional: Focus the field
                }
            }, 100); // Adding a small delay (100ms) before scrolling
        });

    $('#addLogModal').on('shown.bs.modal', function () {
        $('#detailsInput').focus()
    })
</script>
@endscript