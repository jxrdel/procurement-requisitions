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
                    <strong><i class="fa-solid fa-file-pen"></i> Procurement Requisition Form</strong>
                </h1>
                <a href="{{ asset('form_instructions.pdf') }}" target="_blank" class="btn btn-dark">
                    <i class="fa-solid fa-circle-info me-2"></i> Instructions
                </a>
            </div>

            <div class="row mt-6">
                {{-- Requesting Unit --}}
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="requesting_unit_label" class="col-md-4 col-form-label">Requesting Unit</label>
                        <div class="col-md-8">
                            <select disabled required wire:model="requesting_unit"
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

                {{-- Head of Department --}}
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="head_of_department_label" class="col-md-4 col-form-label">Head of Department</label>
                        <div class="col-md-8">
                            <select disabled required wire:model="head_of_department"
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
                {{-- Contact Person --}}
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="contact_person_id_label" class="col-md-4 col-form-label">Contact Person</label>
                        <div class="col-md-8">
                            <select disabled required wire:model="contact_person_id"
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
                {{-- Date Created --}}
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="date_input" class="col-md-4 col-form-label">Date Created</label>
                        <div class="col-md-8">
                            <input disabled autocomplete="off" wire:model="date" type="date"
                                class="form-control @error('date')is-invalid @enderror" id="date_input"
                                aria-describedby="date_input_help" />
                            @error('date')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Contact Person Info (Input) --}}
                {{-- <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="contact_info_input" class="col-md-4 col-form-label">Contact Person Info</label>
                        <div class="col-md-8">
                            <input autocomplete="off" wire:model="contact_info" type="text"
                                class="form-control @error('contact_info')is-invalid @enderror" id="contact_info_input"
                                placeholder="Contact Person Info" aria-describedby="contact_info_help" />
                            @error('contact_info')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                </div> --}}
            </div>

            {{-- <div class="row mt-6">
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="date_input" class="col-md-4 col-form-label">Date Created</label>
                        <div class="col-md-8">
                            <input disabled autocomplete="off" wire:model="date" type="date"
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

            {{-- - Procurement Section Divider and Introduction - --}}
            <div class="divider mt-6">
                <div class="divider-text fw-bold fs-5"><i class="ri-file-text-line me-2"></i>Procurement Request
                </div>
            </div>

            <p class="mt-6 fw-medium text-center">Please ensure this form is submitted with a covering memo
                explaining the
                request</p>

            {{-- Category --}}
            <div class="row mt-6">
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="category_input" class="col-md-4 col-form-label">Category</label>
                        <div class="col-md-8">
                            <select required wire:model="category"
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

            {{-- Justification (Textarea - Full Width) --}}
            <div class="row mt-6">
                <div class="col-md-12">
                    <div class="mb-3 row">
                        <label for="justification_textarea" class="col-md-2 col-form-label">Justification for
                            Request <span class="text-danger">*</span></label>
                        <div class="col-md-10">
                            <textarea wire:model="justification" class="form-control @error('justification')is-invalid @enderror"
                                id="justification_textarea" rows="4"></textarea>
                            @error('justification')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Location and Date Required By --}}
            <div class="row mt-6">
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="location_of_delivery_input" class="col-md-4 col-form-label">Location of
                            Delivery</label>
                        <div class="col-md-8">
                            <input autocomplete="off" wire:model="location_of_delivery" type="text"
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
                        <label for="date_required_by_input" class="col-md-4 col-form-label">Date Required By</label>
                        <div class="col-md-8">
                            <input autocomplete="off" wire:model="date_required_by" type="date"
                                class="form-control @error('date_required_by')is-invalid @enderror"
                                id="date_required_by_input" aria-describedby="date_required_by_help" />
                            @error('date_required_by')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Estimated Value --}}
            <div class="row mt-6">
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="estimated_value_input" class="col-md-4 col-form-label">Estimated Value</label>
                        <div class="col-md-8">
                            <input autocomplete="off" wire:model="estimated_value" type="number" step="0.01"
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

            {{-- - Finance Section and Checkboxes - --}}
            <p class="text-center mt-6 fw-medium">Please contact the Finance & Accounts department to obtain the
                following information <span class="text-danger">*</span></p>

            <div class="row mt-6">
                <div class="col-md-6">
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

                <div wire:ignore class="col-md-6">
                    <div class="mb-3 row">
                        <label for="vote_no_input" class="col-md-4 col-form-label">Vote Number(s)</label>
                        <div class="col mt-3">
                            <select style="width: 100%;" id="voteSelect" class="js-example-basic-multiple"
                                multiple="multiple">

                                @foreach ($votes as $vote)
                                    <option value="{{ $vote->id }}">{{ $vote->number }} | {{ $vote->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider mt-6">
                <div class="divider-text fw-bold fs-5"><i class="ri-list-ordered me-2"></i>Items</div>
            </div>

            <p class="mt-6 fw-medium">For items with multiple specifications, please attach additional
                documentation as necessary <span class="text-danger">*</span></p>

            <div class="row mt-6" x-data="{
                items: $wire.entangle('items'),
                errors: $wire.entangle('validationErrors')
            }">
                <div class="table-responsive">
                    <table class="table table-bordered w-100">
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
                                <th style="width: 80px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                            :class="errors['items.' + index + '.name'] ? 'is-invalid' : ''"
                                            x-model="items[index].name" placeholder="Item Name" required>
                                        <template x-if="errors['items.' + index + '.name']">
                                            <div class="text-danger small"
                                                x-text="errors['items.' + index + '.name'][0]"></div>
                                        </template>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm"
                                            :class="errors['items.' + index + '.qty_in_stock'] ? 'is-invalid' : ''"
                                            x-model="items[index].qty_in_stock" placeholder="0" min="0"
                                            required>
                                        <template x-if="errors['items.' + index + '.qty_in_stock']">
                                            <div class="text-danger small"
                                                x-text="errors['items.' + index + '.qty_in_stock'][0]">
                                            </div>
                                        </template>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm"
                                            :class="errors['items.' + index + '.qty_requesting'] ? 'is-invalid' : ''"
                                            x-model="items[index].qty_requesting" placeholder="0" min="1"
                                            required>
                                        <template x-if="errors['items.' + index + '.qty_requesting']">
                                            <div class="text-danger small"
                                                x-text="errors['items.' + index + '.qty_requesting'][0]">
                                            </div>
                                        </template>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                            :class="errors['items.' + index + '.unit_of_measure'] ? 'is-invalid' : ''"
                                            x-model="items[index].unit_of_measure" placeholder="Unit">
                                        <template x-if="errors['items.' + index + '.unit_of_measure']">
                                            <div class="text-danger small"
                                                x-text="errors['items.' + index + '.unit_of_measure'][0]">
                                            </div>
                                        </template>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                            :class="errors['items.' + index + '.size'] ? 'is-invalid' : ''"
                                            x-model="items[index].size" placeholder="Size">
                                        <template x-if="errors['items.' + index + '.size']">
                                            <div class="text-danger small"
                                                x-text="errors['items.' + index + '.size'][0]"></div>
                                        </template>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                            :class="errors['items.' + index + '.colour'] ? 'is-invalid' : ''"
                                            x-model="items[index].colour" placeholder="Colour">
                                        <template x-if="errors['items.' + index + '.colour']">
                                            <div class="text-danger small"
                                                x-text="errors['items.' + index + '.colour'][0]"></div>
                                        </template>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                            :class="errors['items.' + index + '.brand_model'] ? 'is-invalid' : ''"
                                            x-model="items[index].brand_model" placeholder="Brand/Model">
                                        <template x-if="errors['items.' + index + '.brand_model']">
                                            <div class="text-danger small"
                                                x-text="errors['items.' + index + '.brand_model'][0]">
                                            </div>
                                        </template>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                            :class="errors['items.' + index + '.other'] ? 'is-invalid' : ''"
                                            x-model="items[index].other" placeholder="Other">
                                        <template x-if="errors['items.' + index + '.other']">
                                            <div class="text-danger small"
                                                x-text="errors['items.' + index + '.other'][0]"></div>
                                        </template>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger"
                                            @click="items.splice(index, 1)" :disabled="items.length <= 1"
                                            title="Cannot delete the last item">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="items.length === 0">
                                <tr>
                                    <td colspan="9" class="text-center">No items added. Click the button below to
                                        add an item.</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col d-flex justify-content-center">
                        <button type="button" class="btn btn-primary waves-effect waves-light"
                            @click="items.push({ 
                                name: '', 
                                qty_in_stock: 0, 
                                qty_requesting: 1, 
                                unit_of_measure: '', 
                                size: '', 
                                colour: '', 
                                brand_model: '', 
                                other: '' 
                            })">
                            <span class="fa-solid fa-circle-plus me-1_5"></span>Add Item
                        </button>
                    </div>
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


        $('#voteSelect').select2();

        $('#voteSelect').on('change', function() {
            var selectedValues = $(this).val();
            $wire.set('selected_votes', selectedValues);
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
