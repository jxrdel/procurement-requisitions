<div class="nav-align-top mb-6" style="min-height: 380px">
    <ul wire:ignore class="nav nav-tabs mb-4 nav-fill" role="tablist">
        <li x-on:click="$wire.active_pane = 'procurement1'" wire:ignore class="nav-item mb-1 mb-sm-0">
            <button type="button" @class([
                'nav-link',
                'active' => $this->active_pane === 'procurement1',
            ]) role="tab" data-bs-toggle="tab"
                data-bs-target="#navs-justified-procurement1" aria-controls="navs-justified-procurement1"
                aria-selected="true">
                <i class="bi bi-1-circle-fill me-1_5"></i> Procurement
            </button>
        </li>
        <li x-on:click="$wire.active_pane = 'cost_and_budgeting'" wire:ignore class="nav-item mb-1 mb-sm-0">
            <button type="button" @class([
                'nav-link',
                'active' => $this->active_pane === 'cost_and_budgeting',
            ]) role="tab" data-bs-toggle="tab"
                data-bs-target="#navs-justified-cost_budgeting" aria-controls="navs-justified-cost_budgeting"
                aria-selected="false">
                <i class="bi bi-2-circle-fill me-1_5"></i> Cost & Budgeting
            </button>
        </li>
        <li x-on:click="$wire.active_pane = 'procurement2'" wire:ignore class="nav-item mb-1 mb-sm-0">
            <button type="button" @class([
                'nav-link',
                'active' => $this->active_pane === 'procurement2',
            ]) role="tab" data-bs-toggle="tab"
                data-bs-target="#navs-justified-procurement2" aria-controls="navs-justified-procurement2"
                aria-selected="false">
                <i class="bi bi-3-circle-fill me-1_5"></i> Procurement
            </button>
        </li>
        <li x-on:click="$wire.active_pane = 'votecontrol'" wire:ignore class="nav-item mb-1 mb-sm-0">
            <button type="button" @class(['nav-link', 'active' => $this->active_pane === 'votecontrol']) role="tab" data-bs-toggle="tab"
                data-bs-target="#navs-justified-votecontrol" aria-controls="navs-justified-votecontrol"
                aria-selected="false">
                <i class="bi bi-4-circle-fill me-1_5"></i> Vote Control
            </button>
        </li>
        <li x-on:click="$wire.active_pane = 'checkroom'" wire:ignore class="nav-item mb-1 mb-sm-0">
            <button type="button" @class(['nav-link', 'active' => $this->active_pane === 'checkroom']) role="tab" data-bs-toggle="tab"
                data-bs-target="#navs-justified-check-room" aria-controls="navs-justified-check-room"
                aria-selected="false">
                <i class="bi bi-5-circle-fill me-1_5"></i> Check Room
            </button>
        </li>
        <li x-on:click="$wire.active_pane = 'chequeprocessing'" wire:ignore class="nav-item mb-1 mb-sm-0">
            <button type="button" @class([
                'nav-link',
                'active' => $this->active_pane === 'chequeprocessing',
            ]) role="tab" data-bs-toggle="tab"
                data-bs-target="#navs-justified-cheque-processing" aria-controls="navs-justified-cheque-processing"
                aria-selected="false">
                <i class="bi bi-6-circle-fill me-1_5"></i> Cheque Processing
            </button>
        </li>
    </ul>
    <div wire:ignore.self class="tab-content">
        <div @class([
            'tab-pane fade',
            'show active' => $this->active_pane === 'procurement1',
        ]) id="navs-justified-procurement1" role="tabpanel">

            <div>
                <div class="row mt-8">

                    <div class="col mx-5">
                        <label><strong>Requisition Number:
                            </strong>{{ $this->requisition_no }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Requesting Unit:</strong>
                            {{ $this->requisition->department->name }}</label>
                    </div>
                </div>

                <div class="row mt-7">

                    <div class="col mx-5">
                        <label><strong>File Number / Form:</strong> {{ $this->file_no }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Item:</strong> {{ $this->item }}</label>
                    </div>

                </div>

                <div class="row mt-7">

                    <div class="col mx-5">
                        <label><strong>Source of Funds:</strong>
                            {{ $this->source_of_funds }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Assigned To:</strong>
                            {{ $this->requisition->procurement_officer->name }}</label>
                    </div>

                </div>

                <div class="row mt-7">

                    <div class="col mx-5">
                        <label><strong>Date Assigned to Officer:</strong>
                            {{ $this->getFormattedDate($this->date_assigned) }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Date sent to DPS:</strong>
                            {{ $this->getFormattedDate($this->date_sent_dps) }}</label>
                    </div>

                </div>

                <div class="row mt-7">

                    <div class="col mx-5">
                        <label><strong>PS Approval:</strong>
                            @if ($this->ps_approval == 'Pending')
                                <span class="badge rounded-pill bg-danger fs-6">Pending</span>
                            @else
                                {{ $this->ps_approval }}
                            @endif
                        </label>
                    </div>

                    <div class="col mx-5">
                        @if ($this->sent_to_cb)
                            <label><strong>Date Sent to Cost & Budgeting:</strong>
                                {{ $this->getFormattedDate($this->date_sent_cb) }}</label>
                        @endif
                    </div>

                </div>

                @if ($this->ps_approval == 'Approved')
                    <div class="row mt-7">

                        <div class="col mx-5">
                            <label><strong>Vendor Name:</strong> {{ $this->vendor_name }}</label>
                        </div>

                        <div class="col mx-5">
                            <label><strong>Amount:</strong> {{ $this->amount }}</label>
                        </div>

                    </div>
                @endif

                @if ($this->ps_approval == 'Approval Denied')
                    <div class="row mt-7">

                        <div class="col mx-5">
                            <label><strong>Reason for Denial:</strong>
                                {{ $this->denied_note }}</label>
                        </div>

                    </div>
                @endif



            </div>

        </div>
        <div wire:ignore.self @class([
            'tab-pane fade',
            'show active' => $this->active_pane === 'cost_and_budgeting',
        ]) id="navs-justified-cost_budgeting" role="tabpanel">
            <div>
                <div class="row mt-8">

                    <div class="col mx-5">
                        <label><strong>Date Request Sent to Ministry of Finance:
                            </strong>{{ $this->getFormattedDate($this->date_sent_request_mof) }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Request Number:</strong> {{ $this->request_no }}</label>
                    </div>
                </div>

                <div class="row mt-7">

                    <div class="col mx-5">
                        <label><strong>Release Number:</strong> {{ $this->release_no }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Release Date:</strong>
                            {{ $this->getFormattedDate($this->release_date) }}</label>
                    </div>

                </div>

                <div class="row mt-7">

                    <div class="col mx-5">
                        <label><strong>Change of Vote Number:</strong>
                            {{ $this->change_of_vote_no }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Date Sent to Procurement:</strong>
                            {{ $this->getFormattedDate($this->requisition->cost_budgeting_requisition->date_completed) }}</label>
                    </div>

                </div>

            </div>
        </div>
        <div wire:ignore.self @class([
            'tab-pane fade',
            'show active' => $this->active_pane === 'procurement2',
        ]) id="navs-justified-procurement2" role="tabpanel">


            <div>
                <div class="row mt-8">

                    <div class="col mx-5">
                        <label><strong>Purchase Order Number:
                            </strong>{{ $this->purchase_order_no }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>ETA:</strong> {{ $this->getFormattedDate($this->eta) }} </label>
                    </div>
                </div>

                <div class="row mt-7">

                    <div class="col mx-5">
                        <label><strong>Date Sent to Commit:</strong>
                            {{ $this->getFormattedDate($this->date_sent_commit) }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Invoice Number:</strong> {{ $this->invoice_no }}</label>
                    </div>

                </div>

                <div class="row mt-7">

                    <div class="col mx-5">
                        <label><strong>Date of Invoice Received in the Department:</strong>
                            {{ $this->getFormattedDate($this->date_invoice_received) }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Date Sent to AP:</strong>
                            {{ $this->getFormattedDate($this->date_sent_ap) }}</label>
                    </div>

                </div>

            </div>

        </div>

        <div @class([
            'tab-pane fade',
            'show active' => $this->active_pane === 'votecontrol',
        ]) id="navs-justified-votecontrol" role="tabpanel">

            <div>
                <div class="row mt-8">

                    <div class="col mx-5">
                        <label><strong>Batch Number: </strong>{{ $this->batch_no }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Voucher Bumber:</strong> {{ $this->voucher_no }}</label>
                    </div>
                </div>

                {{-- <div class="row mt-8">

                    <div class="col mx-5">
                        <label><strong>Date Payment Voucher sent to Cheque Room:
                            </strong>{{ $this->getFormattedDateSentChequeroom() }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Date of Cheque:</strong>
                            {{ $this->getFormattedDateOfCheque() }}</label>
                    </div>
                </div>

                <div class="row mt-7">

                    <div class="col mx-5">
                        <label><strong>Cheque Number:</strong> {{ $this->cheque_no }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Date Cheque Forwarded to Cheque Despatch:</strong>
                            {{ $this->getFormattedDateChequeForwarded() }}</label>
                    </div>

                </div>

                @if ($this->requisition->is_completed)
                    <div style="margin-top: 50px" class="row text-center">

                        <h3><strong>Requisition Completed on
                                {{ \Carbon\Carbon::parse($this->date_completed)->format('F jS, Y') }}</strong>
                        </h3>

                    </div>
                @endif --}}

            </div>
        </div>

        <div @class([
            'tab-pane fade',
            'show active' => $this->active_pane === 'checkroom',
        ]) id="navs-justified-check-room" role="tabpanel">
            Hello
        </div>

        <div @class([
            'tab-pane fade',
            'show active' => $this->active_pane === 'chequeprocessing',
        ]) id="navs-justified-cheque-processing" role="tabpanel">
            Hello
        </div>
    </div>
</div>
