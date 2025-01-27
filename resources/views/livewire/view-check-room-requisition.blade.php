<div x-data="{
    isEditing: $wire.entangle('isEditing'),
    showDetails: false,
    voucher_destination: $wire.entangle('voucher_destination'),
}" x-cloak>
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <a href="{{ route('check_room.index') }}" class="btn btn-primary">
                    <i class="ri-arrow-left-circle-line me-1"></i> Back
                </a>
                <h1 class="h3 mb-0 text-gray-800" style="flex: 1; text-align: center;">
                    <strong style="margin-right: 90px"><i class="fa-solid fa-file-circle-plus"></i>
                        {{ $this->requisition->requisition_no }}</strong>
                </h1>
            </div>

            {{-- <div class="row mt-2">

                <div class="col mx-5">
                    <label><strong>Date Received:</strong>
                        {{ $this->requisition->check_room_requisition->created_at->format('F jS, Y') }}</label>
                </div>
            </div> --}}
            <div x-show="isEditing">
                <form wire:submit.prevent="edit">
                    <div id="inputForm">

                        <div class="row mt-7">

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="date_received_from_vc" type="date"
                                        class="form-control @error('date_received_from_vc')is-invalid @enderror"
                                        id="floatingInput" placeholder="Batch Number"
                                        aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date Voucher Received from Vote Control</label>
                                </div>
                                @error('date_received_from_vc')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col">
                                <div class="form-floating form-floating-outline mb-6">
                                    <select wire:model="voucher_destination"
                                        class="form-select @error('voucher_destination')is-invalid @enderror"
                                        id="exampleFormControlSelect1" aria-label="Default select example">
                                        <option value="" selected>Select an Option</option>
                                        <option value="Cheque Processing">Cheque Processing</option>
                                        <option value="Internal Audit">Internal Audit</option>
                                    </select>
                                    <label for="exampleFormControlSelect1">Voucher Sent To</label>
                                    @error('voucher_destination')
                                        <div class="text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                                @error('voucher_destination')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                        </div>

                        <div class="row mb-6" x-show="voucher_destination == 'Internal Audit'" x-transition>

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="date_sent_audit" type="date"
                                        class="form-control @error('date_sent_audit')is-invalid @enderror"
                                        id="floatingInput" placeholder="Batch Number"
                                        aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date Sent to Audit</label>
                                </div>
                                @error('date_sent_audit')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="date_received_from_audit" type="date"
                                        class="form-control @error('date_received_from_audit')is-invalid @enderror"
                                        id="floatingInput" placeholder="Batch Number"
                                        aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date Received from Audit</label>
                                </div>
                                @error('date_received_from_audit')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                        </div>

                        <div class="row">

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="date_sent_chequeprocessing" type="date"
                                        class="form-control @error('date_sent_chequeprocessing')is-invalid @enderror"
                                        id="floatingInput" placeholder="Batch Number"
                                        aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date Voucher Sent to Cheque Processing</label>
                                </div>
                                @error('date_sent_chequeprocessing')
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
                        <label><strong>Date Voucher Received from Vote Control:</strong>
                            {{ $this->getFormattedDate($this->date_received_from_vc) }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Voucher Sent To: </strong>{{ $this->voucher_destination }}</label>
                    </div>
                </div>

                @if ($this->voucher_destination == 'Internal Audit')
                    <div class="row mt-8">

                        <div class="col mx-5">
                            <label><strong>Date Sent to Audit:</strong>
                                {{ $this->getFormattedDate($this->date_sent_audit) }}</label>
                        </div>

                        <div class="col mx-5">
                            <label><strong>Date Received from Audit:
                                </strong>{{ $this->getFormattedDate($this->date_received_from_audit) }}</label>
                        </div>
                    </div>
                @endif

                <div class="row mt-8">

                    <div class="col mx-5">
                        <label><strong>Date Voucher Sent to Cheque Processing:</strong>
                            {{ $this->getFormattedDate($this->date_sent_chequeprocessing) }}</label>
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
                            @if (!$this->cr_requisition->is_completed)
                                <button @disabled($this->isButtonDisabled)
                                    wire:confirm="Are you sure you want to send to Cheque Processing?"
                                    wire:loading.attr="disabled" wire:click="sendToChequeProcessing"
                                    class="btn btn-success waves-effect waves-light" style="width:300px">
                                    <span class="tf-icons ri-mail-send-line me-1_5"></span>Send to Cheque Processing

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

            <div class="mt-6 text-center" x-show="!showDetails" x-cloak>
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

                @livewire('read-only-requisition', ['id' => $this->requisition->id, 'view' => '5'])
            </div>

        </div>
    </div>
</div>
