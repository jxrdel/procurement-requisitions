<div x-data="{ isEditing: $wire.entangle('isEditing') }" x-cloak>
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <a href="{{ route('cost_and_budgeting.index') }}" class="btn btn-primary">
                    <i class="ri-arrow-left-circle-line me-1"></i> Back
                </a>
                <h1 class="h3 mb-0 text-gray-800" style="flex: 1; text-align: center;">
                    <strong style="margin-right: 90px"><i class="fa-solid fa-file-circle-plus"></i>
                        {{ $this->requisition->requisition_no }}</strong>
                </h1>
            </div>

            <div class="row mt-2">

                <div class="col mx-5 fs-5">
                    <label style="text-decoration: underline"><strong>Total:</strong>
                        ${{ number_format($this->total, 2) }}</label>
                </div>
            </div>

            <div x-show="isEditing">
                <form wire:submit.prevent="edit">
                    @foreach ($this->vendors as $index => $vendor)
                        <div class="accordion mt-8" id="accordion{{ $index }}" style="margin-top: 15px">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button x-on:click="$wire.toggleAccordionView({{ $index }})"
                                        class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $index }}" aria-expanded="true"
                                        aria-controls="collapse{{ $index }}">
                                        <strong>Vendor: {{ $vendor['vendor_name'] }} | Amount:
                                            ${{ number_format($vendor['amount'], 2) }}</strong>
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}"
                                    class="accordion-collapse collapse {{ $vendor['accordionView'] }}"
                                    data-bs-parent="#accordion{{ $index }}">
                                    <div class="accordion-body">

                                        <div class="row mt-8">

                                            <div class="col">
                                                <div class="form-floating form-floating-outline">
                                                    <input autocomplete="off"
                                                        wire:model="vendors.{{ $index }}.date_sent_request_mof"
                                                        type="date"
                                                        class="form-control @error('vendors.' . $index . '.date_sent_request_mof')is-invalid @enderror"
                                                        id="floatingInput" aria-describedby="floatingInputHelp" />
                                                    <label for="floatingInput">Date Request Sent to Ministry of
                                                        Finance</label>
                                                </div>
                                                @error('vendors.' . $index . '.date_sent_request_mof')
                                                    <div class="text-danger"> {{ $message }} </div>
                                                @enderror
                                            </div>

                                            <div class="col">

                                            </div>
                                        </div>

                                        <div class="row mt-7">

                                            <div class="col">
                                                <div class="form-floating form-floating-outline">
                                                    <select wire:model="vendors.{{ $index }}.request_category"
                                                        class="form-select @error('vendors.' . $index . '.request_category')is-invalid @enderror"
                                                        id="exampleFormControlSelect1"
                                                        aria-label="Default select example">
                                                        <option value="" selected>Select Request Category</option>
                                                        <option value="ADHOC">ADHOC</option>
                                                        <option value="Recurrent Expenditure">Recurrent Expenditure
                                                        </option>
                                                        <option value="Development Program">Development Program</option>
                                                    </select>
                                                    <label for="exampleFormControlSelect1">Request Category</label>
                                                    @error('vendors.' . $index . '.request_category')
                                                        <div class="text-danger"> {{ $message }} </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-floating form-floating-outline">
                                                    <input autocomplete="off"
                                                        wire:model="vendors.{{ $index }}.request_no"
                                                        type="text"
                                                        class="form-control @error('vendors.' . $index . '.request_no')is-invalid @enderror"
                                                        id="floatingInput" placeholder="Request Number"
                                                        aria-describedby="floatingInputHelp" />
                                                    <label for="floatingInput">Request Number</label>
                                                </div>
                                                @error('vendors.' . $index . '.request_no')
                                                    <div class="text-danger"> {{ $message }} </div>
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="row mt-7">

                                            <div class="col">
                                                <div class="form-floating form-floating-outline">
                                                    <select wire:model="vendors.{{ $index }}.release_type"
                                                        class="form-select @error('vendors.' . $index . '.release_type')is-invalid @enderror"
                                                        id="exampleFormControlSelect1"
                                                        aria-label="Default select example">
                                                        <option value="" selected>Select Release Type</option>
                                                        <option value="Release of Funds">Release of Funds</option>
                                                        <option value="Transfer of Release Funds">Transfer of Release
                                                            Funds</option>
                                                    </select>
                                                    <label for="exampleFormControlSelect1">Release Type</label>
                                                    @error('vendors.' . $index . '.release_type')
                                                        <div class="text-danger"> {{ $message }} </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col">
                                                <div class="form-floating form-floating-outline">
                                                    <input autocomplete="off"
                                                        wire:model="vendors.{{ $index }}.release_no"
                                                        type="text"
                                                        class="form-control @error('vendors.' . $index . '.release_no')is-invalid @enderror"
                                                        id="floatingInput" placeholder="Release Number"
                                                        aria-describedby="floatingInputHelp" />
                                                    <label for="floatingInput">Release Number</label>
                                                </div>
                                                @error('vendors.' . $index . '.release_no')
                                                    <div class="text-danger"> {{ $message }} </div>
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="row mt-7">

                                            <div class="col">
                                                <div class="form-floating form-floating-outline">
                                                    <input autocomplete="off"
                                                        wire:model="vendors.{{ $index }}.release_date"
                                                        type="date"
                                                        class="form-control @error('vendors.' . $index . '.release_date')is-invalid @enderror"
                                                        id="floatingInput" aria-describedby="floatingInputHelp" />
                                                    <label for="floatingInput">Release Date</label>
                                                </div>
                                                @error('vendors.' . $index . '.release_date')
                                                    <div class="text-danger"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <div class="form-floating form-floating-outline">
                                                    <select wire:model="vendors.{{ $index }}.change_of_vote_no"
                                                        class="form-select @error('vendors.' . $index . '.change_of_vote_no')is-invalid @enderror"
                                                        id="exampleFormControlSelect1"
                                                        aria-label="Default select example">
                                                        <option value="" selected>Select a Vote</option>
                                                        @foreach ($votes as $vote)
                                                            <option value="{{ $vote->number }}">{{ $vote->number }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                    <label for="exampleFormControlSelect1">Change of Vote
                                                        Number</label>
                                                    @error('vendors.' . $index . '.change_of_vote_no')
                                                        <div class="text-danger"> {{ $message }} </div>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="row d-flex justify-content-center text-center mt-6">

                        <button class="btn btn-primary waves-effect waves-light mt-5" style="width:100px">
                            <span class="tf-icons ri-save-3-line me-1_5"></span>Save
                        </button>
                        &nbsp;
                        <button type="button" @click="isEditing = ! isEditing"
                            class="btn btn-dark waves-effect waves-light mt-5" style="width: 100px">
                            <span class="tf-icons ri-close-circle-line me-1_5"></span>Cancel
                        </button>
                    </div>
                    {{-- <div id="inputForm">
                        <div class="row mt-8">

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="date_sent_request_mof" type="date"
                                        class="form-control @error('date_sent_request_mof')is-invalid @enderror"
                                        id="floatingInput" aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date Request Sent to Ministry of Finance</label>
                                </div>
                                @error('date_sent_request_mof')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col">

                            </div>
                        </div>

                        <div class="row mt-7">

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <select wire:model="request_category"
                                        class="form-select @error('request_category')is-invalid @enderror"
                                        id="exampleFormControlSelect1" aria-label="Default select example">
                                        <option value="" selected>Select Request Category</option>
                                        <option value="Recurrent Expenditure">Recurrent Expenditure</option>
                                        <option value="Development Program">Development Program</option>
                                    </select>
                                    <label for="exampleFormControlSelect1">Request Category</label>
                                    @error('request_category')
                                        <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="request_no" type="text"
                                        class="form-control @error('request_no')is-invalid @enderror" id="floatingInput"
                                        placeholder="Request Number" aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Request Number</label>
                                </div>
                                @error('request_no')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                        </div>


                        <div class="row mt-7">

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <select wire:model="release_type"
                                        class="form-select @error('release_type')is-invalid @enderror"
                                        id="exampleFormControlSelect1" aria-label="Default select example">
                                        <option value="" selected>Select Release Type</option>
                                        <option value="Release of Funds">Release of Funds</option>
                                        <option value="Transfer of Release Funds">Transfer of Release Funds</option>
                                    </select>
                                    <label for="exampleFormControlSelect1">Release Type</label>
                                    @error('release_type')
                                        <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="release_no" type="text"
                                        class="form-control @error('release_no')is-invalid @enderror" id="floatingInput"
                                        placeholder="Release Number" aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Release Number</label>
                                </div>
                                @error('release_no')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                        </div>
                        <div class="row mt-7">


                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="release_date" type="date"
                                        class="form-control @error('release_date')is-invalid @enderror"
                                        id="floatingInput" aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Release Date</label>
                                </div>
                                @error('release_date')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>
                            <div class="col">
                                <div wire:ignore>
                                    <label style="width:100%" for="covSelect">Change of Vote Number:</label>

                                    <select class="js-example-basic-single form-control" id="covSelect"
                                        style="width: 100%" wire:model="change_of_vote_no">
                                        <option value="" selected>Select a Vote</option>
                                        @foreach ($votes as $vote)
                                            <option value="{{ $vote->number }}">{{ $vote->number }}</option>
                                        @endforeach

                                    </select>
                                </div>
                                @error('change_of_vote_no')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                        </div>

                        <div class="row">

                            <button class="btn btn-primary waves-effect waves-light mx-auto mt-5" style="width:100px">
                                <span class="tf-icons ri-save-3-line me-1_5"></span>Save
                            </button>
                        </div>
                    </div> --}}
                </form>
            </div>


            <div x-show="!isEditing">

                @foreach ($vendors as $vendor)
                    <div class="divider">
                        <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }} -
                            ${{ number_format($vendor['amount'], 2) }}</div>
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
                            <label><strong>Request Category:</strong> {{ $vendor['request_category'] }}</label>
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
                            <label><strong>Change of Vote Number:</strong> {{ $vendor['change_of_vote_no'] }}</label>
                        </div>
                    </div>
                @endforeach

                <div class="row text-center mt-4">
                    <div>
                        @can('edit-records')
                            <button type="button" @click="isEditing = true"
                                class="btn btn-dark waves-effect waves-light" style="width: 100px">
                                <span class="tf-icons ri-edit-box-fill me-1_5"></span>Edit
                            </button>
                            &nbsp;
                            @if (!$this->cb_requisition->is_completed)
                                <button @disabled($this->isButtonDisabled)
                                    wire:confirm="Are you sure you want to send to procurement?"
                                    wire:loading.attr="disabled" wire:click="sendToProcurement"
                                    class="btn btn-success waves-effect waves-light" style="width:250px">
                                    <span class="tf-icons ri-mail-send-line me-1_5"></span>Send to Procurement

                                    <div wire:loading class="spinner-border spinner-border-lg text-white mx-2"
                                        role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>
                            @endif
                        @endcan
                    </div>
                </div>

            </div>

            <hr>

            @livewire('add-log', ['id' => $this->requisition->id])

            <hr>

            <div wire:ignore class="accordion mt-8" id="accordionExample" style="margin-top: 15px">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <strong>Requisition Details</strong>
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse hide"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">

                            <div>
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
                                            {{ $this->requisition->procurement_officer->name ?? 'Not Assigned' }}</label>
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
                                        <label><strong>Date Sent to Cost & Budgeting:</strong>
                                            {{ $this->getDateSentCB() }}</label>
                                    </div>

                                </div>

                                @foreach ($this->requisition->vendors as $index => $vendor)
                                    <div class="row mt-7">

                                        <div class="col mx-5">
                                            <label><strong>Vendor #{{ $index + 1 }}:</strong>
                                                {{ $vendor->vendor_name }}</label>
                                        </div>

                                        <div class="col mx-5">
                                            <label><strong>Amount:</strong> {{ $vendor->amount }}</label>
                                        </div>

                                    </div>
                                @endforeach

                                @if (count($this->requisition->vendors) > 0)
                                    <div class="row mt-7">

                                        <div class="col mx-5">
                                            <label><strong>Total:</strong> ${{ $this->total }}</label>
                                        </div>
                                    </div>
                                @endif


                            </div>
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
            $('#covSelect').select2();

            $('#covSelect').on('change', function() {
                var selectedValue = $(this).val(); // Get selected values as an array
                $wire.set('change_of_vote_no', selectedValue); // Pass selected values to Livewire
            });
        });
    </script>
@endscript
