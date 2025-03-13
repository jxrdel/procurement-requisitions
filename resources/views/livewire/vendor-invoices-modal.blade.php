<!-- Modal -->
<div wire:ignore.self class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center" id="invoiceModalLabel" style="color: black; text-align:center">
                    {{ $this->vendor_name ?? '' }} Invoices | Total:
                    ${{ number_format((float) ($this->vendor->amount ?? 0), 2) }}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: black">
                <form wire:submit.prevent="saveInvoice" action="">

                    <div class="row">

                        <div class="col">
                            <div class="form-floating form-floating-outline">
                                <input required wire:model="date_invoice_received" type="date"
                                    class="form-control @error('date_invoice_received')is-invalid @enderror"
                                    autocomplete="off" id="date_invoice_receivedInput" placeholder="firstname.lastname"
                                    aria-describedby="date_invoice_receivedInputHelp" />
                                <label for="date_invoice_receivedInput">Date Invoice Received</label>
                            </div>
                            @error('date_invoice_received')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col">
                            <div class="form-floating form-floating-outline">
                                <input required wire:model="invoice_no" type="text"
                                    class="form-control @error('invoice_no')is-invalid @enderror" autocomplete="off"
                                    id="invoice_noInput" placeholder="Invoice Number"
                                    aria-describedby="invoice_noInputHelp" />
                                <label for="invoice_noInput">Invoice Number</label>
                            </div>
                            @error('invoice_no')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col">
                            <div class="form-floating form-floating-outline">
                                <input required wire:model="invoice_amount" type="number" step="0.01"
                                    class="form-control @error('invoice_amount')is-invalid @enderror" autocomplete="off"
                                    id="invoice_amountInput" placeholder="Invoice Amount"
                                    aria-describedby="invoice_amountInputHelp" />
                                <label for="invoice_amountInput">Invoice Amount</label>
                            </div>
                            @error('invoice_amount')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col-2">
                            <button class="btn btn-primary mt-1">Add Invoice</button>
                        </div>

                    </div>
                </form>

                <div class="row" style="margin-top: 60px">
                    <h5>Invoices ({{ $invoice_count }})</h5>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Date Invoice Received</th>
                                <th>Invoice Number</th>
                                <th>Invoice Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice['date_invoice_received'] }}</td>
                                    <td>{{ $invoice['invoice_no'] }}</td>
                                    <td>{{ $invoice['invoice_amount'] }}</td>
                                    <td>
                                        <button class="btn btn-danger" wire:click="deleteInvoice({{ $invoice['id'] }})"
                                            wire:confirm="Are you sure you want to delete this invoice?">
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="4">No Invoices</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>



            </div>
            <div class="modal-footer" style="align-items: center">
                <div style="margin:auto">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
