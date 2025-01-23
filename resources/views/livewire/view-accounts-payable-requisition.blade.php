<div x-data="{
    isEditing: $wire.entangle('isEditing'),
    showDetails: false
}" x-cloak>
    @include('add-log')
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


                        <div class="row mt-7">

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="date_received_ap" type="date"
                                        class="form-control @error('date_received_ap')is-invalid @enderror"
                                        id="floatingInput" aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date Received From Procurement</label>
                                </div>
                                @error('date_received_ap')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="date_sent_vc" type="date"
                                        class="form-control @error('date_sent_vc')is-invalid @enderror"
                                        id="floatingInput" placeholder="Voucher Number"
                                        aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date Sent to Vote Control</label>
                                </div>
                                @error('date_sent_vc')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
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
                        <label><strong>Date Received From Procurement:</strong>
                            {{ $this->getFormattedDate($this->date_received_ap) }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Date Sent to Vote Control:</strong>
                            {{ $this->getFormattedDate($this->date_sent_vc) }}</label>
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

            <div>
                <hr>

                @can('edit-records')
                    <div class="row mt-5">
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addLogModal"
                            class="btn btn-dark waves-effect waves-light w-25 m-auto">
                            <span class="tf-icons ri-file-add-line me-1_5"></span>Add Log
                        </a>
                    </div>
                @endcan

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
                                    <td>{{ $log->details }}</td>
                                    <td class="text-center">

                                        <button @cannot('delete-records') disabled @endcannot
                                            wire:confirm="Are you sure you want to delete this log?"
                                            wire:click="deleteLog({{ $log->id }})" type="button"
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
            </div>

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


@script
    <script>
        $(document).ready(function() {

            window.addEventListener('close-log-modal', event => {
                $('#addLogModal').modal('hide');
            })
        });
    </script>
@endscript
