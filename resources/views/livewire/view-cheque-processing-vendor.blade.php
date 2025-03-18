<div x-data="{
    isEditing: $wire.entangle('isEditing'),
    showcheques: false,
}" x-cloak>
    <div class="card">
        <div class="card-body">

            <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <a href="{{ route('cheque_processing.index') }}" class="btn btn-primary">
                    <i class="ri-arrow-left-circle-line me-1"></i> Back
                </a>
                <h1 class="h3 mb-0 text-gray-800" style="flex: 1; text-align: center;">
                    <strong style="margin-right: 90px"><i class="fa-solid fa-file-circle-plus"></i>
                        {{ $this->vendor->vendor_name }} |
                        ${{ number_format($this->invoices->sum('invoice_amount'), 2) }}</strong>
                </h1>
            </div>

            <div x-show="isEditing">
                <form wire:submit.prevent="edit">
                    <div id="inputForm">


                        <table class="table table-bordered table-hover" style="margin-top: 50px">
                            <thead class="text-center">
                                <tr>
                                    <th>Cheque Number</th>
                                    <th>Cheque Amount</th>
                                    <th>Cheque Date</th>
                                    <th>Date Processed</th>
                                    <th>Date Sent to Dispatch</th>
                                    <th>Invoice Number</th>
                                    <th style="width: 10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($this->cheques as $index => $row)
                                    <tr>
                                        <td>
                                            <input type="text"
                                                class="form-control @error('cheques.' . $index . '.cheque_no') is-invalid @enderror"
                                                wire:model="cheques.{{ $index }}.cheque_no"
                                                style="width: 100%;color:black;" required>
                                            @error('cheques.' . $index . '.cheque_no')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.01"
                                                class="form-control @error('cheques.' . $index . '.cheque_amount') is-invalid @enderror"
                                                wire:model="cheques.{{ $index }}.cheque_amount"
                                                style="width: 100%;color:black;" required>
                                            @error('cheques.' . $index . '.cheque_amount')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="date"
                                                class="form-control @error('cheques.' . $index . '.date_of_cheque') is-invalid @enderror"
                                                wire:model="cheques.{{ $index }}.date_of_cheque"
                                                style="width: 100%;color:black;" required>
                                            @error('cheques.' . $index . '.date_of_cheque')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="date"
                                                class="form-control @error('cheques.' . $index . '.date_cheque_processed') is-invalid @enderror"
                                                wire:model="cheques.{{ $index }}.date_cheque_processed"
                                                style="width: 100%;color:black;" required>
                                            @error('cheques.' . $index . '.date_cheque_processed')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="date"
                                                class="form-control @error('cheques.' . $index . '.date_sent_dispatch') is-invalid @enderror"
                                                wire:model="cheques.{{ $index }}.date_sent_dispatch"
                                                style="width: 100%;color:black;" required>
                                            @error('cheques.' . $index . '.date_sent_dispatch')
                                                <div class="text-danger"> {{ $message }} </div>
                                            @enderror
                                        </td>
                                        <td>
                                            <div class="">
                                                <select wire:model="cheques.{{ $index }}.invoice_no"
                                                    class="form-control @error('cheques.' . $index . '.invoice_no')is-invalid @enderror"
                                                    id="exampleFormControlSelect1" aria-label="Default select example">
                                                    <option value=""></option>
                                                    @foreach ($invoices as $invoice)
                                                        <option value="{{ $invoice->invoice_no }}">
                                                            {{ $invoice->invoice_no }}</option>
                                                    @endforeach
                                                </select>
                                                @error('cheques.' . $index . '.invoice_no')
                                                    <div class="text-danger"> {{ $message }} </div>
                                                @enderror
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" wire:click="removeCheque({{ $index }})"
                                                class="btn btn-danger btn-icon shadow-sm"><i
                                                    class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center;">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="text-center">
                            <button type="button" wire:click="addCheque"
                                class="btn rounded-pill btn-icon btn-primary mx-auto mt-2">
                                <span class="tf-icons ri-add-line ri-22px"></span>
                            </button>
                        </div>

                        {{-- <div class="row mt-7">

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="date_cheque_processed" type="date"
                                        class="form-control @error('date_cheque_processed')is-invalid @enderror"
                                        id="floatingInput" placeholder="Cheque Number"
                                        aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date Cheque Processed</label>
                                </div>
                                @error('date_cheque_processed')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="cheque_no" type="text"
                                        class="form-control @error('cheque_no')is-invalid @enderror" id="floatingInput"
                                        placeholder="Cheque Number" aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Cheque Number</label>
                                </div>
                                @error('cheque_no')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                        </div>

                        <div class="row mt-6 mb-6">

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="date_of_cheque" type="date"
                                        class="form-control @error('date_of_cheque')is-invalid @enderror"
                                        id="floatingInput" aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Cheque Date</label>
                                </div>
                                @error('date_of_cheque')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <input autocomplete="off" wire:model="date_sent_dispatch" type="date"
                                        class="form-control @error('date_sent_dispatch')is-invalid @enderror"
                                        id="floatingInput" placeholder="Cheque Number"
                                        aria-describedby="floatingInputHelp" />
                                    <label for="floatingInput">Date Cheque Sent to Cheque Dispatch</label>
                                </div>
                                @error('date_sent_dispatch')
                                    <div class="text-danger"> {{ $message }} </div>
                                @enderror
                            </div>

                        </div> --}}

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

                <h5>Cheques ({{ count($this->cheques) }})</h5>
                <table class="table table-bordered table-hover" style="margin-top: 10px">
                    <thead class="text-center">
                        <tr>
                            <th>Cheque Number</th>
                            <th>Cheque Amount</th>
                            <th>Cheque Date</th>
                            <th>Date Processed</th>
                            <th>Date Sent to Dispatch</th>
                            <th>Invoice Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->cheques as $index => $row)
                            <tr class="text-center">
                                <td>{{ $row['cheque_no'] }}</td>
                                <td>{{ $row['cheque_amount'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($row['date_of_cheque'])->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($row['date_cheque_processed'])->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($row['date_sent_dispatch'])->format('d/m/Y') }}</td>
                                <td>{{ $row['invoice_no'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center;">No cheques entered</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="row text-center mt-5">
                    <div>
                        @can('edit-records')
                            <button type="button" @click="isEditing = true" class="btn btn-dark waves-effect waves-light"
                                style="width: 100px">
                                <span class="tf-icons ri-edit-box-fill me-1_5"></span>Edit
                            </button>
                            &nbsp;
                            @if (!$this->cp_vendor->is_completed)
                                <button @disabled($this->isButtonDisabled)
                                    wire:confirm="Are you sure you want to complete the vendor?"
                                    wire:loading.attr="disabled" wire:click="completeVendor"
                                    class="btn btn-success waves-effect waves-light" style="width:220px">
                                    <span class="ri-checkbox-circle-line me-1_5"></span>Complete

                                    <div wire:loading class="spinner-border spinner-border-lg text-white mx-2"
                                        wire:target="completeVendor" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>
                            @endif
                        @endcan
                    </div>
                </div>

            </div>

            <div class="accordion mt-8" id="accordionInvoices" style="margin-top: 15px">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button x-on:click="$wire.toggleAccordionView" class="accordion-button" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseInvoices" aria-expanded="true"
                            aria-controls="collapseInvoices">
                            <strong>Invoices ({{ count($invoices) }})</strong>
                        </button>
                    </h2>
                    <div id="collapseInvoices" class="accordion-collapse collapse {{ $accordionView }}"
                        data-bs-parent="#accordionInvoices">
                        <div class="accordion-body">

                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Invoice Number</th>
                                        <th>Invoice Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->invoice_no }}</td>
                                            <td>${{ number_format($invoice->invoice_amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="6">No Invoices</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            @livewire('add-log', ['id' => $this->requisition->id])


            <hr>

            <div class="mt-6 text-center" x-show="!showcheques">
                <button type="button" @click="showcheques = true" class="btn btn-danger waves-effect waves-light"
                    style="width: 250px">
                    <span class="ri-add-circle-line me-1_5"></span>Show Requisition Details
                </button>
            </div>

            <div class="mt-6" x-show="showcheques" x-cloak>

                <div class="text-center mb-5">
                    <button type="button" @click="showcheques = ! showcheques"
                        class="btn btn-dark waves-effect waves-light" style="width: 250px">
                        <span class="ri-subtract-line me-1_5"></span>Hide Details
                    </button>
                </div>

                @livewire('read-only-requisition', ['id' => $this->requisition->id, 'view' => '6'])
            </div>

        </div>
    </div>
</div>
