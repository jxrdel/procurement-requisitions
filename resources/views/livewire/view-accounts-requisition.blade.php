<div x-data="{ isEditing: $wire.entangle('isEditing')  }" x-cloak>
    <div class="card">
        <div class="card-body">
                    
            <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <a href="{{route('accounts_requisitions.index')}}" class="btn btn-primary">
                    <i class="ri-arrow-left-circle-line me-1"></i> Back
                </a>
                <h1 class="h3 mb-0 text-gray-800" style="flex: 1; text-align: center;">
                    <strong style="margin-right: 90px"><i class="fa-solid fa-file-circle-plus"></i> {{$this->requisition->requisition_no}}</strong>
                </h1>
            </div>
            <div x-show="isEditing">
                <form wire:submit.prevent="edit">
                    <div id="inputForm">
                        <div class="row mt-7">
                    
                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input
                                    autocomplete="off"
                                    wire:model="date_sent_chequeroom"
                                    type="date"
                                    class="form-control @error('date_sent_chequeroom')is-invalid @enderror"
                                    id="floatingInput"
                                    aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date Payment Voucher sent to Check Room</label>
                                </div>
                                @error('date_sent_chequeroom')<div class="text-danger"> {{ $message }} </div>@enderror
                            </div>
                
                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input
                                    autocomplete="off"
                                    wire:model="date_of_cheque"
                                    type="date"
                                    class="form-control @error('date_of_cheque')is-invalid @enderror"
                                    id="floatingInput"
                                    aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date of Cheque</label>
                                </div>
                                @error('date_of_cheque')<div class="text-danger"> {{ $message }} </div>@enderror
                            </div>
                        </div>
                        
                                
                        <div class="row mt-7">
                
                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input
                                    autocomplete="off"
                                    wire:model="cheque_no"
                                    type="text"
                                    class="form-control @error('cheque_no')is-invalid @enderror"
                                    id="floatingInput"
                                    placeholder="Cheque Number"
                                    aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Cheque Number</label>
                                </div>
                                @error('cheque_no')<div class="text-danger"> {{ $message }} </div>@enderror
                            </div>
                    
                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input
                                    autocomplete="off"
                                    wire:model="date_cheque_forwarded"
                                    type="date"
                                    class="form-control @error('date_cheque_forwarded')is-invalid @enderror"
                                    id="floatingInput"
                                    aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date Cheque Forwarded to Cheque Despatch</label>
                                </div>
                                @error('date_cheque_forwarded')<div class="text-danger"> {{ $message }} </div>@enderror
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
                        <label><strong>Date Payment Voucher sent to Cheque Room: </strong>{{$this->getFormattedDateSentChequeroom()}}</label>
                    </div>
            
                    <div class="col mx-5">
                        <label><strong>Date of Cheque:</strong> {{$this->getFormattedDateOfCheque()}}</label>
                    </div>
                </div>
                    
                <div class="row mt-7">
                                    
                    <div class="col mx-5">
                        <label><strong>Cheque Number:</strong> {{$this->cheque_no}}</label>
                    </div>
            
                    <div class="col mx-5">
                        <label><strong>Date Cheque Forwarded to Cheque Despatch:</strong> {{$this->getFormattedDateChequeForwarded()}}</label>
                    </div>
            
                </div>
                
                <div class="row text-center mt-5">
                    <div>
                        <button type="button" @click="isEditing = true" class="btn btn-dark waves-effect waves-light" style="width: 100px">
                            <span class="tf-icons ri-edit-box-fill me-1_5"></span>Edit
                        </button>
                        &nbsp;
                        @if (!$this->accounts_requisition->is_completed)
                        <button @disabled($this->isButtonDisabled) 
                            wire:confirm="Are you sure you want to complete the requisition?"
                            wire:click="completeRequisition"
                            class="btn btn-success waves-effect waves-light" style="width:220px">
                            <span class="ri-checkbox-circle-line me-1_5"></span>Complete Requisition
                        </button>
                            
                        @endif
                    </div>
                </div>
                
            </div>

        </div>
    </div>
</div>
