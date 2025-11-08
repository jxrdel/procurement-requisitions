<div x-data="{
    isEditing: $wire.entangle('isEditing'),
    showDetails: false
}" x-cloak>
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <a href="{{ route('accounts_payable.index') }}" class="btn btn-primary">
                    <i class="ri-arrow-left-circle-line me-1"></i> Back
                </a>
                <h1 class="h3 mb-0 text-gray-800" style="flex: 1; text-align: center;">
                    <strong style="margin-right: 90px"><i class="fa-solid fa-file-circle-plus"></i>
                        {{ $this->requisition->requisition_no }}</strong>
                </h1>
            </div>

            <div x-show="isEditing">
                <form wire:submit.prevent="edit">
                    <div id="inputForm">

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
                                            <div class="row mt-7">
                                                <div class="col">
                                                    <div class="form-floating form-floating-outline">
                                                        <input autocomplete="off"
                                                            wire:model="vendors.{{ $index }}.date_received_ap"
                                                            type="date"
                                                            class="form-control @error('vendors.' . $index . '.date_received_ap')is-invalid @enderror"
                                                            id="floatingInput" aria-describedby="floatingInputHelp" />
                                                        <label for="floatingInput">Date Received From
                                                            Procurement</label>
                                                    </div>
                                                    @error('vendors.' . $index . '.date_received_ap')
                                                        <div class="text-danger"> {{ $message }} </div>
                                                    @enderror
                                                </div>

                                                <div class="col">
                                                    <div class="form-floating form-floating-outline">
                                                        <input autocomplete="off"
                                                            wire:model="vendors.{{ $index }}.date_sent_vc"
                                                            type="date"
                                                            class="form-control @error('vendors.' . $index . '.date_sent_vc')is-invalid @enderror"
                                                            id="floatingInput" placeholder="Voucher Number"
                                                            aria-describedby="floatingInputHelp" />
                                                        <label for="floatingInput">Date Sent to Vote
                                                            Control</label>
                                                    </div>
                                                    @error('vendors.' . $index . '.date_sent_vc')
                                                        <div class="text-danger"> {{ $message }} </div>
                                                    @enderror
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
                    </div>
                </form>
            </div>


            <div x-show="!isEditing">


                @foreach ($vendors as $vendor)
                    <div class="row mt-5">
                        <div class="divider">
                            <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }} -
                                ${{ number_format($vendor['amount'], 2) }}</div>
                        </div>

                        <div class="row mt-8">

                            <div class="col mx-5">
                                <label><strong>Date Received From Procurement For Commitment :</strong>
                                    {{ $this->getFormattedDate($vendor['date_received_ap']) }}</label>
                            </div>

                            <div class="col mx-5">
                                <label><strong>Date Sent to Vote Control For Commitment:</strong>
                                    {{ $this->getFormattedDate($vendor['date_sent_vc']) }}</label>
                            </div>
                        </div>

                        <div class="row mt-8">

                            <div class="col mx-5">
                                <label><strong>Date Received From Procurement For Invoices :</strong>
                                    {{ $this->getFormattedDate($vendor['date_received_ap_invoices']) }}</label>
                            </div>

                            <div class="col mx-5">
                                <label><strong>Date Sent to Vote Control For Invoices:</strong>
                                    {{ $this->getFormattedDate($vendor['date_sent_vc_invoices']) }}</label>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="row text-center mt-5">
                    <div>
                        @can('edit-records')
                            <button type="button" @click="isEditing = true" class="btn btn-dark waves-effect waves-light"
                                style="width: 100px">
                                <span class="tf-icons ri-edit-box-fill me-1_5"></span>Edit
                            </button>
                            &nbsp;
                            @if (!$this->ap_requisition->is_completed)
                                <button @disabled($this->isButtonDisabled)
                                    wire:confirm="Are you sure you want to send this requisition to Vote Control?"
                                    wire:loading.attr="disabled" wire:click="sendToVoteControl"
                                    class="btn btn-success waves-effect waves-light" style="width:250px">
                                    <span class="tf-icons ri-mail-send-line me-1_5"></span>Send to Vote Control

                                    <div wire:loading wire:target="sendToVoteControl"
                                        class="spinner-border spinner-border-lg text-white mx-2" role="status">
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

            <div class="mt-6 text-center" x-show="!showDetails">
                <button type="button" @click="showDetails = true" class="btn btn-danger waves-effect waves-light"
                    style="width: 250px">
                    <span class="ri-add-circle-line me-1_5"></span>Show Requisition Details
                </button>
            </div>

            <div class="mt-6" x-show="showDetails" x-cloak>

                <div class="text-center mb-5">
                    <button type="button" @click="showDetails = ! showDetails"
                        class="btn btn-dark waves-effect waves-light" style="width: 250px">
                        <span class="ri-subtract-line me-1_5"></span>Hide Details
                    </button>
                </div>

                @livewire('read-only-requisition', ['id' => $this->requisition->id, 'view' => '3'])
            </div>

        </div>
    </div>
</div>
