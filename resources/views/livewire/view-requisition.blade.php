<div>
    @include('add-log')
        <div class="card" 
        x-data="{ 
        sent_to_dfa: $wire.entangle('sent_to_dfa'),
        isEditingProcurement1: $wire.entangle('isEditingProcurement1'),
        isEditingProcurement2: $wire.entangle('isEditingProcurement2')  
        }"
         x-cloak>
            <div class="card-body">
                
                <div class="d-sm-flex align-items-center justify-content-between mb-5">
                    <a href="{{ route('requisitions.index') }}" class="btn btn-dark">
                        <i class="ri-arrow-left-circle-line me-1"></i> Back
                    </a>
                    
                    <h1 class="h3 mb-0 text-gray-800" style="flex: 1; text-align: center;">
                        <strong>View Requisition</strong> 
                        @if ($this->requisition->is_completed)
                            <span style="background-color: #47a102 !important;" class="badge rounded-pill bg-success fs-5">Completed</span>
                        @endif
                    </h1>
                </div>
                <div class="nav-align-top mb-6" style="min-height: 350px">
                  <ul wire:ignore class="nav nav-tabs mb-4 nav-fill" role="tablist">
                    <li  x-on:click="$wire.active_pane = 'procurement1'" wire:ignore class="nav-item mb-1 mb-sm-0">
                      <button
                        type="button"
                        @class(['nav-link', 'active' => $this->active_pane === 'procurement1'])
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-justified-procurement1"
                        aria-controls="navs-justified-procurement1"
                        aria-selected="true">
                        <i class="bi bi-1-circle-fill me-1_5"></i> Procurement
                      </button>
                    </li>
                    <li x-on:click="$wire.active_pane = 'cost_and_budgeting'"  wire:ignore class="nav-item mb-1 mb-sm-0">
                      <button
                        type="button"
                        @class(['nav-link', 'active' => $this->active_pane === 'cost_and_budgeting'])
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-justified-cost_budgeting"
                        aria-controls="navs-justified-cost_budgeting"
                        aria-selected="false">
                        <i class="bi bi-2-circle-fill me-1_5"></i> Cost & Budgeting
                      </button>
                    </li>
                    <li x-on:click="$wire.active_pane = 'procurement2'" wire:ignore class="nav-item mb-1 mb-sm-0">
                      <button
                        type="button"
                        @class(['nav-link', 'active' => $this->active_pane === 'procurement2'])
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-justified-procurement2"
                        aria-controls="navs-justified-procurement2"
                        aria-selected="false">
                        <i class="bi bi-3-circle-fill me-1_5"></i> Procurement
                      </button>
                    </li>
                    <li x-on:click="$wire.active_pane = 'accounts'" wire:ignore class="nav-item mb-1 mb-sm-0">
                      <button
                        type="button"
                        @class(['nav-link', 'active' => $this->active_pane === 'accounts'])
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-justified-accounts"
                        aria-controls="navs-justified-accounts"
                        aria-selected="false">
                        <i class="bi bi-4-circle-fill me-1_5"></i> Cheque Dispatch
                      </button>
                    </li>
                  </ul>
                  <div wire:ignore.self class="tab-content">
                    <div 
                        @class(['tab-pane fade', 'show active' => $this->active_pane === 'procurement1'])
                        id="navs-justified-procurement1" 
                        role="tabpanel">
                        
                            <div id="procurementView1">
                                <form wire:submit.prevent="edit">
                                    <div class="row text-center">
                                        <div x-show="!isEditingProcurement1">
                                            <button type="button" @click="isEditingProcurement1 = ! isEditingProcurement1" class="btn btn-dark waves-effect waves-light" style="width: 100px">
                                                <span class="tf-icons ri-edit-box-fill me-1_5"></span>Edit
                                            </button>
                                        </div>
                                    </div>
                    
                                    <div x-transition x-show="!isEditingProcurement1">
                                        <div class="row mt-8">
                                                            
                                            <div class="col mx-5">
                                                <label><strong>Requisition Number: </strong>{{$this->requisition_no}}</label>
                                            </div>
                                    
                                            <div class="col mx-5">
                                                <label><strong>Requesting Unit:</strong> {{$this->requisition->department->name}}</label>
                                            </div>
                                        </div>
                                            
                                        <div class="row mt-7">
                                                            
                                            <div class="col mx-5">
                                                <label><strong>File Number:</strong> {{$this->file_number}}</label>
                                            </div>
                                    
                                            <div class="col mx-5">
                                                <label><strong>Item:</strong> {{$this->item}}</label>
                                            </div>
                                    
                                        </div>
                                    
                                        <div class="row mt-7">
                                                            
                                            <div class="col mx-5">
                                                <label><strong>Source of Funds:</strong> {{$this->source_of_funds}}</label>
                                            </div>
                                    
                                            <div class="col mx-5">
                                                <label><strong>Assigned To:</strong> {{$this->requisition->procurement_officer->name}}</label>
                                            </div>
                                            
                                        </div>
                                    
                                        <div class="row mt-7">
                                                            
                                            <div class="col mx-5">
                                                <label><strong>Date Sent to PS:</strong> {{$this->getFormattedDateSentPs()}}</label>
                                            </div>
                                    
                                            <div class="col mx-5">
                                                <label><strong>PS Approval:</strong> {{$this->ps_approval}}</label>
                                            </div>
                                    
                                        </div>
                                    
                                        {{-- <div class="row mt-7">
                                                            
                                            <div class="col mx-5">
                                                <label><strong>Sent to DFA:</strong> {{$this->sent_to_dfa}}</label>
                                            </div>
                                    
                                            <div class="col mx-5">
                                                <label><strong>Date Sent to DFA:</strong> {{$this->getFormattedDateSentDfa()}}</label>
                                            </div>
                                    
                                        </div> --}}
                                        
                                    </div>
                                    
                                    <div x-transition x-show="isEditingProcurement1">
                                        <div class="row mt-8">
                                
                                            <div class="col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <input
                                                    autocomplete="off"
                                                    wire:model="requisition_no"
                                                    type="text"
                                                    class="form-control @error('requisition_no')is-invalid @enderror"
                                                    id="floatingInput"
                                                    placeholder="Requisition Number"
                                                    aria-describedby="floatingInputHelp" />
                                                    <label for="floatingInput">Requisition Number</label>
                                                </div>
                                                @error('requisition_no')<div class="text-danger"> {{ $message }} </div>@enderror
                                            </div>
                                            <div class="col-md-6">
                                                <div>
                                                    <label wire:ignore style="width:100%" for="unitSelect">Requesting Unit:
                                                    
                                                        <select wire:model="requesting_unit" class="js-example-basic-single form-control" id="unitSelect" style="width: 100%">
                                                            <option value="" selected>Select a Unit</option>
                                                            @foreach ($departments as $department)
                                                                <option value="{{ $department->id }}">{{ $department->name}}</option>
                                                            @endforeach
                                            
                                                        </select>
                                                    </label>
                                                </div>
                                                @error('requesting_unit')<div class="text-danger"> {{ $message }} </div>@enderror
                                            </div>
                                        </div>
                                            
                                        <div class="row mt-7">
                                
                                            <div class="col">
                                                <div class="form-floating form-floating-outline">
                                                    <input
                                                    autocomplete="off"
                                                    wire:model="file_number"
                                                    type="text"
                                                    class="form-control @error('file_number')is-invalid @enderror"
                                                    id="floatingInput"
                                                    placeholder="File Number"
                                                    aria-describedby="floatingInputHelp" />
                                                    <label for="floatingInput">File Number</label>
                                                </div>
                                                @error('file_number')<div class="text-danger"> {{ $message }} </div>@enderror
                                            </div>
                                
                                            <div class="col">
                                                <div class="form-floating form-floating-outline">
                                                    <input
                                                    autocomplete="off"
                                                    wire:model="item"
                                                    type="text"
                                                    class="form-control @error('item')is-invalid @enderror"
                                                    id="floatingInput"
                                                    placeholder="ex. SSL Certificate"
                                                    aria-describedby="floatingInputHelp" />
                                                    <label for="floatingInput">Item</label>
                                                </div>
                                                @error('item')<div class="text-danger"> {{ $message }} </div>@enderror
                                            </div>
                                        </div>
                        
                                        <div class="row mt-7">
                                
                                            <div class="col-md-6">
                                                <div class="form-floating form-floating-outline">
                                                    <input
                                                    autocomplete="off"
                                                    wire:model="source_of_funds"
                                                    type="text"
                                                    class="form-control @error('source_of_funds')is-invalid @enderror"
                                                    id="floatingInput"
                                                    placeholder="Source of Funds"
                                                    aria-describedby="floatingInputHelp" />
                                                    <label for="floatingInput">Source of Funds</label>
                                                </div>
                                                @error('source_of_funds')<div class="text-danger"> {{ $message }} </div>@enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-floating form-floating-outline mb-6">
                                                <select required wire:model="assigned_to" class="form-select @error('assigned_to')is-invalid @enderror" id="exampleFormControlSelect1" aria-label="Default select example">
                                                    <option value="Not Sent" selected>Select Employee</option>
                                                    @foreach ($staff as $staff)
                                                        <option value="{{ $staff->id }}">{{ $staff->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label for="exampleFormControlSelect1">Assigned To</label>
                                                @error('assigned_to')<div class="text-danger"> {{ $message }} </div>@enderror
                                                </div>
                                            </div>
                                        </div>
                        
                                        <div class="row">
                        
                                            <div class="col">
                                                <div class="form-floating form-floating-outline">
                                                    <input
                                                    autocomplete="off"
                                                    wire:model="date_sent_ps"
                                                    type="date"
                                                    class="form-control @error('date_sent_ps')is-invalid @enderror"
                                                    id="floatingInput"
                                                    aria-describedby="floatingInputHelp" />
                                                    <label for="floatingInput">Date Sent to PS</label>
                                                </div>
                                                @error('date_sent_ps')<div class="text-danger"> {{ $message }} </div>@enderror
                                            </div>
                        
                                            <div class="col">
                                                <div class="form-floating form-floating-outline mb-6">
                                                <select required wire:model="ps_approval" class="form-select @error('ps_approval')is-invalid @enderror" id="exampleFormControlSelect1" aria-label="Default select example">
                                                    <option value="Not Sent" selected>Not Sent</option>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Approved">Approved</option>
                                                    <option value="Approval Denied">Approval Denied</option>
                                                </select>
                                                <label for="exampleFormControlSelect1">PS Approval</label>
                                                @error('ps_approval')<div class="text-danger"> {{ $message }} </div>@enderror
                                                </div>
                                            </div>
                                        </div>
                        
                                        <div class="row text-center">
                                            
                                            <div x-show="isEditingProcurement1">
                                                <button class="btn btn-primary waves-effect waves-light" style="width: 100px">
                                                    <span class="tf-icons ri-save-3-line me-1_5"></span>Save
                                                </button>
                                                &nbsp;
                                                <button type="button" @click="isEditingProcurement1 = ! isEditingProcurement1" class="btn btn-dark waves-effect waves-light" style="width: 100px">
                                                    <span class="tf-icons ri-close-circle-line me-1_5"></span>Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                
                                
                                @if (!$this->sent_to_dfa)
                                <div class="row mt-8" x-show="!isEditingProcurement1">
                                    <button @disabled($this->isSendCBButtonDisabled) wire:confirm="Are you sure you want to send to cost & budgeting?" 
                                        wire:loading.attr="disabled"  type="button" wire:click="sendToCB" class="btn btn-success waves-effect waves-light m-auto" 
                                        style="width: 300px;">
                                        <span class="tf-icons ri-mail-send-line me-1_5"></span>Send to Cost & Budgeting
                
                                    <div wire:loading class="spinner-border spinner-border-lg text-white mx-2" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    </button>
                                </div>
                                @endif
                            </div>
                    </div>
                    <div  wire:ignore.self
                        @class(['tab-pane fade', 'show active' => $this->active_pane === 'cost_and_budgeting'])
                        id="navs-justified-cost_budgeting" 
                        role="tabpanel">
                        <div>
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
                            
                        </div>
                    </div>
                    <div  wire:ignore.self
                        @class(['tab-pane fade', 'show active' => $this->active_pane === 'procurement2'])
                        id="navs-justified-procurement2" 
                        role="tabpanel">
                        
                        
                        <div id="procurementView2">
                            <form wire:submit.prevent="editProcurement2">
                                <div class="row text-center">
                                    <div x-show="!isEditingProcurement2">
                                        <button type="button" @click="isEditingProcurement2 = ! isEditingProcurement2" class="btn btn-dark waves-effect waves-light" style="width: 100px">
                                            <span class="tf-icons ri-edit-box-fill me-1_5"></span>Edit
                                        </button>
                                    </div>
                                </div>
        
                                <div x-transition x-show="!isEditingProcurement2">
                                    <div class="row mt-8">
                                                        
                                        <div class="col mx-5">
                                            <label><strong>Purchase Order Number: </strong>{{$this->purchase_order_no}}</label>
                                        </div>
                                
                                        <div class="col mx-5">
                                            <label><strong>ETA:</strong> {{$this->getFormattedEta()}} </label>
                                        </div>
                                    </div>
                                        
                                    <div class="row mt-7">
                                                        
                                        <div class="col mx-5">
                                            <label><strong>Date Sent to Commit:</strong> {{$this->getFormattedDateSentCommit()}}</label>
                                        </div>
                                
                                        <div class="col mx-5">
                                            <label><strong>Invoice Number:</strong> {{$this->invoice_no}}</label>
                                        </div>
                                
                                    </div>
                                
                                    <div class="row mt-7">
                                                        
                                        <div class="col mx-5">
                                            <label><strong>Date of Invoice Received in the Department:</strong> {{$this->getFormattedDateInvoiceReceived()}}</label>
                                        </div>
                                
                                        <div class="col mx-5">
                                            <label><strong>Date Sent to AP:</strong> {{$this->getFormattedDateSentAP()}}</label>
                                        </div>
                                        
                                    </div>

                                    @if (!$this->requisition->accounts_requisition)
                                        <div class="row mt-8">
                                            <button @disabled($this->isButtonProcurement2Disabled)  wire:confirm="Are you sure you want to send to cheque dispatch?" wire:loading.attr="disabled"  type="button" wire:click="sendToAccounts" class="btn btn-success waves-effect waves-light m-auto" style="width: 300px">
                                                <span class="tf-icons ri-mail-send-line me-1_5"></span>Send to Cheque Dispatch
            
                                            <div wire:loading class="spinner-border spinner-border-lg text-white mx-2" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            </button>
                                        </div>
                                    @endif
                                    
                                </div>
                                
                                <div x-transition x-show="isEditingProcurement2">
                                    <div class="row mt-8">
        
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input
                                                autocomplete="off"
                                                wire:model="purchase_order_no"
                                                type="text"
                                                class="form-control @error('purchase_order_no')is-invalid @enderror"
                                                id="floatingInput"
                                                placeholder="Purchase Order Number"
                                                aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">Purchase Order Number</label>
                                            </div>
                                            @error('purchase_order_no')<div class="text-danger"> {{ $message }} </div>@enderror
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input
                                                autocomplete="off"
                                                wire:model="eta"
                                                type="date"
                                                class="form-control @error('eta')is-invalid @enderror"
                                                id="floatingInput"
                                                aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">ETA</label>
                                            </div>
                                            @error('eta')<div class="text-danger"> {{ $message }} </div>@enderror
                                        </div>
                                    </div>
                                        
                                    <div class="row mt-7">
        
                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input
                                                autocomplete="off"
                                                wire:model="date_sent_commit"
                                                type="date"
                                                class="form-control @error('date_sent_commit')is-invalid @enderror"
                                                id="floatingInput"
                                                aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">Date Sent to Commit</label>
                                            </div>
                                            @error('date_sent_commit')<div class="text-danger"> {{ $message }} </div>@enderror
                                        </div>
        
                                        <div class="col">
                                            <div class="form-floating form-floating-outline">
                                                <input
                                                autocomplete="off"
                                                wire:model="invoice_no"
                                                type="text"
                                                class="form-control @error('invoice_no')is-invalid @enderror"
                                                id="floatingInput"
                                                placeholder="Invoice Number"
                                                aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">Invoice Number</label>
                                            </div>
                                            @error('invoice_no')<div class="text-danger"> {{ $message }} </div>@enderror
                                        </div>
                                    </div>
        
                                    <div class="row mt-7">
        
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input
                                                autocomplete="off"
                                                wire:model="date_invoice_received"
                                                type="date"
                                                class="form-control @error('date_invoice_received')is-invalid @enderror"
                                                id="floatingInput"
                                                aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">Date of Invoice Received in the Department</label>
                                            </div>
                                            @error('date_invoice_received')<div class="text-danger"> {{ $message }} </div>@enderror
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input
                                                autocomplete="off"
                                                wire:model="date_sent_ap"
                                                type="date"
                                                class="form-control @error('date_sent_ap')is-invalid @enderror"
                                                id="floatingInput"
                                                aria-describedby="floatingInputHelp" />
                                                <label for="floatingInput">Date Sent to AP</label>
                                            </div>
                                            @error('date_sent_ap')<div class="text-danger"> {{ $message }} </div>@enderror
                                        </div>
                                    </div>
        
                                    <div class="row text-center mt-8">
                                        
                                        <div x-show="isEditingProcurement2">
                                            <button class="btn btn-primary waves-effect waves-light" style="width: 100px">
                                                <span class="tf-icons ri-save-3-line me-1_5"></span>Save
                                            </button>
                                            &nbsp;
                                            <button type="button" @click="isEditingProcurement2 = ! isEditingProcurement2" class="btn btn-dark waves-effect waves-light" style="width: 100px">
                                                <span class="tf-icons ri-close-circle-line me-1_5"></span>Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                            
                        </div>
                    </div>
                    
                    <div 
                        @class(['tab-pane fade', 'show active' => $this->active_pane === 'accounts'])
                        id="navs-justified-accounts" 
                        role="tabpanel">
                        
                        <div>
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
                            
                        </div>
                    </div>
                  </div>
                </div>
                    
                <div id="file-uploads">
                
                    <div class="divider" style="margin-top: 40px">
                        <div class="divider-text">
                            <i class="fa-solid fa-file-arrow-up fs-4"></i>
                        </div>
                    </div>

                    <div class="row">
                        <h4 class="text-center fw-bold">File Uploads</h4>
                    </div>

                
                    <div class="row">
                        <div class="col" style="text-align: center;padding-bottom:10px">
                                @error('upload')<div class="text-danger fw-bold"> {{ $message }} </div>@enderror
                        
                                <input wire:model="upload" type="file" class="form-control" style="display: inline;width: 400px;height:45px">
                                <button wire:click.prevent="uploadFile()" class="btn btn-primary" wire:loading.attr="disabled" wire:target="upload" style="width: 8rem"><i class="fas fa-plus me-2"></i> Upload</button>
                                <span wire:loading wire:target="upload">Uploading...</span>
                        </div>
                    </div>
                    
                    <div class="row ">

                        <div class="demo-inline-spacing d-flex justify-content-center align-items-center">
                            <div class="list-group list-group-flush" style="width: 500px">

                                @forelse ($uploads as $upload)

                                    <div class="list-group list-group-flush list-group-item-action" style="width: 100%;cursor: default;">
                                        <div class="list-group-item d-flex justify-content-between align-items-center" style="border: none;">
                                            <a class="text-dark text-decoration-underline" href="{{ Storage::url($upload->file_path) }}" target="_blank">{{$upload->file_name}}</a>
                                            {{-- <button type="button" class="btn btn-danger">
                                                <i class="ri-delete-bin-2-line me-1"></i> Delete
                                            </button> --}}
                                            <a href="javascript:void(0)" wire:confirm="Are you sure you want to delete this file?" wire:click="deleteFile({{$upload->id}})">
                                                <i class="ri-close-large-line text-danger fw-bold"></i>
                                            </a>

                                        </div>
                                    </div>
                                @empty
                                    <div class="list-group list-group-flush list-group-item-action" style="width: 100%;cursor: default;">
                                        <div class="list-group-item" style="border: none;">
                                            <p class="text-center">No files uploaded</p>

                                        </div>
                                    </div>
                                @endforelse

                            </div>
                        </div>
                    </div>
                </div>
                    
                <div class="divider" style="margin-top: 40px">
                    <div class="divider-text">
                        <i class="fa-solid fa-file-pen fs-4"></i>
                    </div>
                </div>

                <div class="row">
                    <h4 class="text-center fw-bold">Status Log</h4>
                </div>

                    
                <div class="row">
                    <a href="javascript:void(0);"  data-bs-toggle="modal" data-bs-target="#addLogModal" class="btn btn-primary waves-effect waves-light w-25 m-auto">
                        <span class="tf-icons ri-file-add-line me-1_5"></span>Add Log
                    </a>
                </div>
                
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
                            <td>{{$log->details}}</td>
                            <td class="text-center">
                                
                                <button wire:click="removeLog({{$index}})" type="button" class="btn btn-danger">
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
        </div>
</div>


@script
<script>
    $(document).ready(function() {
        // Initialize select2
        
        
        $('#unitSelect').select2();
        
        $('#unitSelect').on('change', function() {
            var selectedValue = $(this).val(); // Get selected values as an array
            $wire.set('requesting_unit', selectedValue); // Pass selected values to Livewire
            $wire.set('active_pane', 'procurement1');
        });
    });

    
    window.addEventListener('close-log-modal', event => {
            $('#addLogModal').modal('hide');
        })
    
    
    $wire.on('scrollToError', () => {
            // Wait for Livewire to finish rendering the error fields
            setTimeout(() => {
                const firstErrorElement = document.querySelector('.is-invalid');
                if (firstErrorElement) {
                    firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstErrorElement.focus(); // Optional: Focus the field
                }
            }, 100); // Adding a small delay (100ms) before scrolling
        });
        

    $('#addLogModal').on('shown.bs.modal', function () {
        $('#detailsInput').focus()
    })
</script>
@endscript