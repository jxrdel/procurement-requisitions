<div x-data="{ isEditing: $wire.entangle('isEditing') }" x-cloak>
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <a href="{{ route('queue') }}" class="btn btn-primary">
                    <i class="ri-arrow-left-circle-line me-1"></i> Back
                </a>
                <h1 class="h3 mb-0 text-gray-800" style="flex: 1; text-align: center;">
                    <strong style="margin-right: 90px"><i class="fa-solid fa-file-circle-plus"></i>
                        {{ $this->requisition->requisition_no }}</strong>
                </h1>
            </div>

            <div class="row mt-2 align-items-center">
                <div class="col-md-4 mx-5 fs-5">
                    <label style="text-decoration: underline"><strong>Total:</strong>
                        ${{ number_format($this->total, 2) }}</label>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center" wire:ignore>
                        <label for="sofSelect" class="me-2 mb-0 text-nowrap"><strong>Source of Funds:</strong></label>
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
                                                        <option value="Development Program">Development Program</option>
                                                        <option value="Recurrent Expenditure">Recurrent Expenditure
                                                        </option>
                                                        <option value="Transfer of Release Funds">Transfer of Release
                                                            Funds</option>
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
                                            <div class="col" wire:ignore>
                                                <label for="voteSelect{{ $index }}" class="form-label">Change
                                                    of Vote Number</label>
                                                <select id="voteSelect{{ $index }}"
                                                    class="form-select vote-select" multiple style="width: 100%">
                                                    @foreach ($votes as $vote)
                                                        <option value="{{ $vote->id }}"
                                                            {{ in_array($vote->id, $vendor['selected_votes']) ? 'selected' : '' }}>
                                                            {{ $vote->number }}
                                                        </option>
                                                    @endforeach
                                                </select>
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
                            <label><strong>Change of Vote Number:</strong>
                                @foreach ($this->requisition->vendors()->find($vendor['id'])->votes as $vote)
                                    <span class="badge bg-label-primary">{{ $vote->number }}</span>
                                @endforeach
                            </label>
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

                                {{-- 🔹 Row 3: Date Received by Procurement & Assigned To --}}
                                <div class="row mt-6">
                                    <div class="col-md-6">
                                        <label><strong>Date Received by Procurement:</strong>
                                            {{ $this->getFormattedDate($this->requisition->date_received_procurement) }}</label>
                                    </div>
                                    <div class="col">
                                        <label><strong>Assigned To:</strong>
                                            {{ $this->requisition->procurement_officer->name ?? 'Not Assigned' }}</label>
                                    </div>
                                </div>

                                {{-- 🔹 Row 4: Date Assigned to Officer & Date Sent to AOV Procurement --}}
                                <div class="row mt-6">
                                    <div class="col">
                                        <label><strong>Date Assigned to Officer:</strong>
                                            {{ $this->getFormattedDateAssigned() }}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label><strong>Date Sent to AOV Procurement:</strong>
                                            {{ $this->getFormattedDate($this->requisition->date_sent_aov_procurement) }}</label>
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

                                {{-- 🔹 Row 7: Note to PS & Note to PS Date --}}
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
                                @if ($this->requisition->sent_to_cb)
                                    <div class="row mt-6">
                                        <div class="col-md-6">
                                            <label><strong>Date Sent to Cost & Budgeting:</strong>
                                                {{ $this->getFormattedDate($this->requisition->date_sent_cb) }}</label>
                                        </div>
                                    </div>
                                @endif

                                {{-- 🔹 Row 13: Reason for Denial --}}
                                @if ($this->ps_approval == 'Approval Denied')
                                    <div class="row mt-6">
                                        <div class="col">
                                            <label><strong>Reason for Denial:</strong>
                                                {{ $this->requisition->denied_note }}</label>
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
            initSelect2();

            $('#sofSelect').select2();
            $('#sofSelect').on('change', function() {
                var selectedValue = $(this).val();
                $wire.set('source_of_funds', selectedValue);
            });

            document.addEventListener('livewire:update', function() {
                initSelect2();
                $('#sofSelect').select2();
            });
        });

        function initSelect2() {
            $('.vote-select').each(function(index) {
                if ($(this).data('select2-initialized')) {
                    return;
                }
                $(this).data('select2-initialized', true);

                $(this).select2();
                $(this).on('change', function(e) {
                    var data = $(this).select2("val");
                    @this.set('vendors.' + index + '.selected_votes', data);
                });
            });
        }
    </script>
@endscript
