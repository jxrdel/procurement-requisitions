<div>
    @include('livewire.add-item-modal')
    {{-- @include('livewire.accept-form-modal')
    @include('livewire.decline-form-modal') --}}
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <a href="{{ route('requisition_forms.index') }}" class="btn btn-primary">
                    <i class="ri-arrow-left-circle-line me-1"></i> Back
                </a>
                <h1 class="h3 mb-0 text-gray-800" style="flex: 1; text-align: center;">
                    <strong style="margin-right: 90px"><i class="fa-solid fa-file-circle-plus"></i> Create
                        Requisition Form</strong>
                </h1>
            </div>

            <div class="row mt-6">

                <div class="col">
                    <div class="form-floating form-floating-outline">
                        <select required wire:model="requesting_unit"
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
                        <select required wire:model="head_of_department"
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
                        <select required wire:model="contact_person_id"
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
                        <input autocomplete="off" wire:model="date" type="date"
                            class="form-control @error('date')is-invalid @enderror" id="floatingInput"
                            aria-describedby="floatingInputHelp" />
                        <label for="floatingInput">Date</label>
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
                    <div class="form-floating form-floating-outline">
                        <input autocomplete="off" wire:model="justification" type="file"
                            class="form-control @error('justification')is-invalid @enderror" id="floatingInput"
                            aria-describedby="floatingInputHelp" />
                        <label for="floatingInput">Justification for Request <span class="text-danger">*</span></label>
                    </div>
                    @error('justification')
                        <div class="text-danger"> {{ $message }} </div>
                    @enderror
                </div>

                <div class="col">
                    <div class="form-floating form-floating-outline">
                        <input autocomplete="off" wire:model="location_of_delivery" type="text"
                            class="form-control @error('location_of_delivery')is-invalid @enderror" id="floatingInput"
                            placeholder="Location of Delivery" aria-describedby="floatingInputHelp" />
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
                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addItemModal"
                    class="btn btn-primary waves-effect waves-light w-25 m-auto">
                    <span class="fa-solid fa-file-circle-plus me-1_5"></span>Add Item
                </a>
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
                                    <button class="btn btn-danger mx-auto"
                                        wire:click="removeItem({{ $key }})"><i
                                            class="fa-solid fa-trash-can"></i></button>
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
                            wire:model="availability_of_funds">
                    </div>
                    <div class="form-check mt-4">
                        <label class="form-check-label" for="defaultCheck2"> Verified by Accounts </label>
                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck2"
                            wire:model="verified_by_accounts">
                    </div>
                </div>

                <div class="col">
                    <div class="form-floating form-floating-outline">
                        <input autocomplete="off" wire:model="vote_no" type="text"
                            class="form-control @error('vote_no')is-invalid @enderror" id="floatingInput"
                            placeholder="Vote Number" aria-describedby="floatingInputHelp" />
                        <label for="floatingInput">Vote Number</label>
                    </div>
                    @error('vote_no')
                        <div class="text-danger"> {{ $message }} </div>
                    @enderror
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
                        style="display: inline;width: 400px;height:45px">
                    <span wire:loading wire:target="uploads">Uploading...</span>
                </div>
            </div>

            {{-- <div class="divider">
                <div class="divider-text fw-bold fs-5">Head of Department Approval</div>
            </div>

            <div class="row mt-8">
                <div class="text-center">
                    <button data-bs-toggle="modal" data-bs-target="#acceptRequisitionForm"
                        class="btn btn-success waves-effect waves-light" style="width: 100px">
                        <span class="tf-icons ri-checkbox-circle-line me-1_5"></span>Accept
                    </button>
                    <button class="btn btn-danger waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#declineRequisitionForm" style="width: 100px">
                        <span class="tf-icons ri-close-circle-line me-1_5"></span>Decline
                    </button>
                </div>
            </div> --}}

            <form wire:submit.prevent="save">
                <div class="row mt-8">
                    <button type="submit" wire:loading.attr="disabled" wire:target="save,uploads,justification"
                        class="btn btn-primary waves-effect waves-light m-auto" style="width: 100px">
                        <span class="tf-icons ri-save-3-line me-1_5"></span>Save
                    </button>
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
    </script>
@endscript
