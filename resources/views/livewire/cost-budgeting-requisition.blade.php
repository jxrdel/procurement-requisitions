<div x-data="{ isEditing: $wire.entangle('isEditing')  }" x-cloak>
    <div class="card">
        <div class="card-body">
                    
            <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <a href="{{route('cost_and_budgeting.index')}}" class="btn btn-primary">
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
                                    wire:model="date_sent_request_mof"
                                    type="date"
                                    class="form-control @error('date_sent_request_mof')is-invalid @enderror"
                                    id="floatingInput"
                                    {{-- placeholder="File Number" --}}
                                    aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date Request Sent to Ministry of Finance</label>
                                </div>
                                @error('date_sent_request_mof')<div class="text-danger"> {{ $message }} </div>@enderror
                            </div>
                
                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input
                                    autocomplete="off"
                                    wire:model="request_no"
                                    type="text"
                                    class="form-control @error('request_no')is-invalid @enderror"
                                    id="floatingInput"
                                    placeholder="Request Number"
                                    aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Request Number</label>
                                </div>
                                @error('request_no')<div class="text-danger"> {{ $message }} </div>@enderror
                            </div>
                        </div>
                        
                                
                        <div class="row mt-7">
                
                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input
                                    autocomplete="off"
                                    wire:model="release_no"
                                    type="text"
                                    class="form-control @error('release_no')is-invalid @enderror"
                                    id="floatingInput"
                                    placeholder="Release Number"
                                    aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Release Number</label>
                                </div>
                                @error('release_no')<div class="text-danger"> {{ $message }} </div>@enderror
                            </div>
                    
                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input
                                    autocomplete="off"
                                    wire:model="release_date"
                                    type="date"
                                    class="form-control @error('release_date')is-invalid @enderror"
                                    id="floatingInput"
                                    {{-- placeholder="File Number" --}}
                                    aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Release Date</label>
                                </div>
                                @error('release_date')<div class="text-danger"> {{ $message }} </div>@enderror
                            </div>
            
                        </div>
                        <div class="row mt-7">
                
                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input
                                    autocomplete="off"
                                    wire:model="change_of_vote_no"
                                    type="text"
                                    class="form-control @error('change_of_vote_no')is-invalid @enderror"
                                    id="floatingInput"
                                    placeholder="Change of Vote Number"
                                    aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Change of Vote Number</label>
                                </div>
                                @error('change_of_vote_no')<div class="text-danger"> {{ $message }} </div>@enderror
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
                        <label><strong>Date Request Sent to Ministry of Finance: </strong>{{$this->getFormattedDateSentMOF()}}</label>
                    </div>
            
                    <div class="col mx-5">
                        <label><strong>Request Number:</strong> {{$this->request_no}}</label>
                    </div>
                </div>
                    
                <div class="row mt-7">
                                    
                    <div class="col mx-5">
                        <label><strong>Release Number:</strong> {{$this->release_no}}</label>
                    </div>
            
                    <div class="col mx-5">
                        <label><strong>Release Date:</strong> {{$this->getFormattedReleaseDate()}}</label>
                    </div>
            
                </div>
            
                <div class="row mt-7">
                                    
                    <div class="col mx-5">
                        <label><strong>Change of Vote Number:</strong> {{$this->change_of_vote_no}}</label>
                    </div>
            
                    <div class="col mx-5">
                    </div>
                    
                </div>
                
                <div class="row text-center">
                    <div>
                        <button type="button" @click="isEditing = true" class="btn btn-dark waves-effect waves-light" style="width: 100px">
                            <span class="tf-icons ri-edit-box-fill me-1_5"></span>Edit
                        </button>
                        &nbsp;
                        @if (!$this->cb_requisition->is_completed)
                        <button @disabled($this->isButtonDisabled) 
                            wire:confirm="Are you sure you want to send to procurement?"
                            wire:click="sendToProcurement"
                            class="btn btn-success waves-effect waves-light" style="width:220px">
                            <span class="tf-icons ri-mail-send-line me-1_5"></span>Send to Procurement
                        </button>
                            
                        @endif
                    </div>
                </div>
                
            </div>

        </div>
    </div>
</div>
