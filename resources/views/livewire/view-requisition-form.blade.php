<div>
    @include('livewire.add-item-modal')
    @include('livewire.accept-form-modal')
    @include('livewire.decline-form-modal')
    <div class="card">
        <div class="card-body" x-data="{ isEditing: $wire.entangle('isEditing') }">



            <form wire:submit.prevent="save">

                <div class="d-sm-flex align-items-center justify-content-between mb-5">

                    {{-- Left Column: Back Button --}}
                    <div class="col-3 col-sm-2 text-start">
                        <a href="{{ route('requisition_forms.index') }}" class="btn btn-primary">
                            <i class="ri-arrow-left-circle-line me-1"></i> Back
                        </a>
                    </div>

                    {{-- Center Column: Heading --}}
                    <div class="col-6 col-sm-8 text-center">
                        <h1 class="h3 mb-0 text-gray-800">
                            <strong><i class="fa-solid fa-file-invoice"></i>
                                {{ $requisitionForm->form_code }}</strong>
                        </h1>
                    </div>

                    {{-- Right Column: Save + Edit Buttons --}}
                    <div class="col-3 col-sm-2 text-end d-flex justify-content-end gap-2">
                        <button type="submit" x-show="isEditing" class="btn btn-sm btn-primary"
                            x-bind:class="isEditing ? '' : 'd-none'"> <i class="fa-solid fa-save me-1"></i> Save
                        </button>

                        <button type="button" @click="isEditing = !isEditing"
                            x-bind:class="isEditing ? 'btn-danger' : 'btn-success'" class="btn btn-sm">
                            <i x-bind:class="isEditing ? 'fa-solid fa-xmark' : 'fa-solid fa-pen-to-square'"
                                class="me-1_5"></i>
                            <span x-text="isEditing ? 'Cancel' : 'Edit'"></span>
                        </button>
                    </div>
                </div>
                <div class="row mt-6">

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <select required wire:model="requesting_unit" disabled
                                class="form-select @error('requesting_unit')is-invalid @enderror"
                                id="exampleFormControlSelect1" aria-label="Default select example">
                                <option value="">Select a Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                            <label for="exampleFormControlSelect1">Requesting Unit</label>
                            @error('requesting_unit')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <select required wire:model="head_of_department" disabled
                                class="form-select @error('head_of_department')is-invalid @enderror"
                                id="exampleFormControlSelect1" aria-label="Default select example">
                                <option value="">Select a User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <label for="exampleFormControlSelect1">Head of Department</label>
                            @error('head_of_department')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-6">

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <select required wire:model="contact_person_id" disabled
                                class="form-select @error('contact_person_id')is-invalid @enderror"
                                id="exampleFormControlSelect1" aria-label="Default select example">
                                <option value="">Select a User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <label for="exampleFormControlSelect1">Contact Person</label>
                            @error('contact_person_id')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="contact_info" type="text"
                                x-bind:disabled="!isEditing"
                                class="form-control @error('contact_info')is-invalid @enderror" id="floatingInput"
                                placeholder="Contact Person Info" aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Contact Person Info</label>
                        </div>
                        @error('contact_info')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-6">

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="date" type="date" disabled
                                class="form-control @error('date')is-invalid @enderror" id="floatingInput"
                                aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Date Created</label>
                        </div>
                        @error('date')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col">
                    </div>
                </div>

                <div class="row mt-6">

                    <div class="col">
                        @if ($requisitionForm->justification_path)
                            <div class="mt-2">
                                <a href="{{ Storage::url($requisitionForm->justification_path) }}" target="_blank"
                                    class="text-primary">
                                    View Justification Document
                                </a>
                            </div>
                        @else
                            <div class="form-floating form-floating-outline">
                                <input autocomplete="off" wire:model="justification" type="file"
                                    x-bind:disabled="!isEditing"
                                    class="form-control @error('justification')is-invalid @enderror" id="floatingInput"
                                    aria-describedby="floatingInputHelp" />
                                <label for="floatingInput">Justification for Request <span
                                        class="text-danger">*</span></label>
                            </div>
                            @error('justification')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        @endif
                    </div>

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="location_of_delivery" type="text"
                                x-bind:disabled="!isEditing"
                                class="form-control @error('location_of_delivery')is-invalid @enderror"
                                id="floatingInput" placeholder="Location of Delivery"
                                aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Location of Delivery</label>
                        </div>
                        @error('location_of_delivery')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-6">

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="date_required_by" type="date"
                                x-bind:disabled="!isEditing"
                                class="form-control @error('date_required_by')is-invalid @enderror" id="floatingInput"
                                aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Date Required By</label>
                        </div>
                        @error('date_required_by')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="estimated_value" type="number"
                                x-bind:disabled="!isEditing"
                                class="form-control @error('estimated_value')is-invalid @enderror" id="floatingInput"
                                placeholder="Estimated Value" aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Estimated Value</label>
                        </div>
                        @error('estimated_value')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>

                <div class="divider mt-6">
                    <div class="divider-text fw-bold fs-5"><i class="ri-file-text-line me-2"></i>Items</div>
                </div>

                <div class="row">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#addItemModal"
                        x-bind:class="{ 'pointer-events-none opacity-50': !isEditing }" x-bind:disabled="!isEditing"
                        class="btn btn-primary waves-effect waves-light w-25 m-auto">
                        <span class="fa-solid fa-file-circle-plus me-1_5"></span>Add Item
                    </button>
                </div>

                <p class="mt-6 fw-medium">For items with multiple specifications, please attach additional
                    documentation
                    as necessary <span class="text-danger">*</span></p>

                <div class="row mt-6">
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
                                <th>Actions</th>
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
                                        <button type="button" class="btn btn-danger mx-auto"
                                            x-bind:disabled="!isEditing" wire:click="removeItem({{ $key }})"
                                            wire:confirm="Are you sure you want to delete this item?">
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

                <p class="text-center mt-6 fw-medium">Please contact the Finance & Accounts department to
                    obtain the
                    following information <span class="text-danger">*</span></p>

                <div class="row mt-6">
                    <div class="col">
                        <div class="form-check mt-4">
                            <label class="form-check-label" for="defaultCheck1"> Availability of Funds </label>
                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1"
                                x-bind:disabled="!isEditing" wire:model="availability_of_funds">
                        </div>
                        <div class="form-check mt-4">
                            <label class="form-check-label" for="defaultCheck2"> Verified by Accounts </label>
                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck2"
                                x-bind:disabled="!isEditing" wire:model="verified_by_accounts">
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-floating form-floating-outline">
                            <input autocomplete="off" wire:model="vote_no" type="text"
                                x-bind:disabled="!isEditing"
                                class="form-control @error('vote_no')is-invalid @enderror" id="floatingInput"
                                placeholder="Vote Number" aria-describedby="floatingInputHelp" />
                            <label for="floatingInput">Vote Number</label>
                        </div>
                        @error('vote_no')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>
                </div>
                <div class="row mt-8" x-show="!isEditing">
                    <div class="text-center m-auto">
                        <button type="button" class="btn btn-sm btn-info">
                            <i class="tf-icons ri-mail-send-line me-1_5"></i>
                            <span>Send to Head of Department</span>
                        </button>
                    </div>
                </div>


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
                            x-bind:disabled="!isEditing" style="display: inline;width: 400px;height:45px">
                        <span wire:loading wire:target="uploads">Uploading...</span>
                    </div>
                </div>

                <div class="divider">
                    <div class="divider-text fw-bold fs-5 mt-4"><i class="fa-solid fa-file-pen me-2"></i>Logs
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
                            @forelse($requisitionForm->logs as $log)
                                <tr>
                                    <td>{{ $log->details }}</td>
                                    <td>{{ $log->created_at->format('d/m/Y') }}</td>
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
                <div class="row mt-8" x-show="isEditing">
                    <div class="text-center m-auto">
                        <button type="submit" wire:loading.attr="disabled" wire:target="save,uploads,justification"
                            class="btn btn-primary waves-effect waves-light" style="width: 100px">
                            <span class="tf-icons ri-save-3-line me-1_5"></span>Save
                        </button>
                        &nbsp;
                        <button type="button" @click="isEditing = false"
                            class="btn btn-dark waves-effect waves-light" style="width: 100px">
                            <span class="tf-icons ri-close-circle-line me-1_5"></span>Cancel
                        </button>
                    </div>
                </div>
                <div class="row mt-8" x-show="!isEditing">
                    <div class="text-center m-auto">
                        <button type="button" @click="isEditing = !isEditing"
                            x-bind:class="isEditing ? 'btn-danger' : 'btn-success'" class="btn btn-sm">
                            <i class="fa-solid fa-pen-to-square me-1_5"></i>
                            <span x-text="isEditing ? 'Cancel' : 'Edit'"></span>
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

@script
    <script>
        $(document).ready(function() {
            window.addEventListener('close-add-item-modal', event => {
                $('#addItemModal').modal('hide');
            })
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
