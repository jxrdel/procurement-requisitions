<div x-data="{
    isEditing: $wire.entangle('isEditing'),
    showDetails: false
}" x-cloak>
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <a href="{{ route('vote_control.index') }}" class="btn btn-primary">
                    <i class="ri-arrow-left-circle-line me-1"></i> Back
                </a>
                <h1 class="h3 mb-0 text-gray-800" style="flex: 1; text-align: center;">
                    <strong style="margin-right: 90px"><i class="fa-solid fa-file-circle-plus"></i>
                        {{ $this->vendor->vendor_name }} | ${{ number_format($this->vendor->amount, 2) }}</strong>
                </h1>
            </div>

            {{-- <div class="row mt-2">

                <div class="col mx-5">
                    <label><strong>Date Received:</strong> {{ $this->getDateSentVC() }}</label>
                </div>
            </div> --}}
            <div x-show="isEditing">
                <form wire:submit.prevent="edit">
                    <div id="inputForm">


                        <div class="row mt-7">

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="batch_no" type="text"
                                        class="form-control @error('batch_no')is-invalid @enderror" id="floatingInput"
                                        placeholder="Batch Number" aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Batch Number</label>
                                </div>
                                @error('batch_no')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="voucher_no" type="text"
                                        class="form-control @error('voucher_no')is-invalid @enderror" id="floatingInput"
                                        placeholder="Voucher Number" aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Voucher Number</label>
                                </div>
                                @error('voucher_no')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                        </div>

                        <div class="row mt-7">

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="date_sent_checkstaff" type="date"
                                        class="form-control @error('date_sent_checkstaff')is-invalid @enderror"
                                        id="floatingInput" aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date Sent to Check Staff</label>
                                </div>
                                @error('date_sent_checkstaff')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col">
                            </div>

                        </div>


                        <div class="row">

                            <button class="btn btn-primary waves-effect waves-light mx-auto mt-5" style="width:100px">
                                <span class="tf-icons ri-save-3-line me-1_5"></span>Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>


            <div x-show="!isEditing">
                <div class="row mt-8">

                    <div class="col mx-5">
                        <label><strong>Batch Number:</strong> {{ $this->batch_no }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Voucher Number: </strong>{{ $this->voucher_no }}</label>
                    </div>
                </div>
                <div class="row mt-7">

                    <div class="col mx-5">
                        <label><strong>Date Sent to Check Staff:</strong>
                            {{ $this->getFormattedDate($this->date_sent_checkstaff) }}</label>
                    </div>

                    <div class="col mx-5">
                    </div>
                </div>

                <div class="row text-center mt-5">
                    <div>
                        @can('edit-records')
                            <button type="button" @click="isEditing = true" class="btn btn-dark waves-effect waves-light"
                                style="width: 100px">
                                <span class="tf-icons ri-edit-box-fill me-1_5"></span>Edit
                            </button>
                            &nbsp;
                            @if (!$this->vc_vendor->is_completed)
                                <button @disabled($this->isButtonDisabled)
                                    wire:confirm="Are you sure you want to send this requisition to Check Staff?"
                                    wire:loading.attr="disabled" wire:click="sendToCheckStaff"
                                    class="btn btn-success waves-effect waves-light" style="width:250px">
                                    <span class="tf-icons ri-mail-send-line me-1_5"></span>Send to Check Staff

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

                @livewire('read-only-requisition', ['id' => $this->requisition->id, 'view' => '4'])
            </div>

        </div>
    </div>
</div>
