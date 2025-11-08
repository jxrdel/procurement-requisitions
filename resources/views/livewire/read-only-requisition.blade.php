<div class="nav-align-top mb-6" style="min-height: 380px">
    <ul wire:ignore class="nav nav-tabs mb-4 nav-fill" role="tablist">
        <li x-on:click="$wire.panes = '1'" wire:ignore class="nav-item mb-1 mb-sm-0">
            <button type="button" @class(['nav-link', 'active' => $this->panes === '1']) role="tab" data-bs-toggle="tab"
                data-bs-target="#navs-justified-procurement-1" aria-controls="navs-justified-procurement-1"
                aria-selected="true">
                <i class="bi bi-1-circle-fill me-1_5"></i> Procurement
            </button>
        </li>
        <li x-on:click="$wire.panes = '2'" wire:ignore class="nav-item mb-1 mb-sm-0">
            <button type="button" @class(['nav-link', 'active' => $this->panes === '2']) role="tab" data-bs-toggle="tab"
                data-bs-target="#navs-justified-cost_budgeting" aria-controls="navs-justified-cost_budgeting"
                aria-selected="false">
                <i class="bi bi-2-circle-fill me-1_5"></i> Cost & Budgeting
            </button>
        </li>
        <li x-on:click="$wire.panes = '3'" wire:ignore class="nav-item mb-1 mb-sm-0">
            <button type="button" @class(['nav-link', 'active' => $this->panes === '3']) role="tab" data-bs-toggle="tab"
                data-bs-target="#navs-justified-procurement-2" aria-controls="navs-justified-procurement-2"
                aria-selected="false">
                <i class="bi bi-3-circle-fill me-1_5"></i> Procurement
            </button>
        </li>
        @if ($this->panes >= 4)
            <li x-on:click="$wire.panes = '4'" wire:ignore class="nav-item mb-1 mb-sm-0">
                <button type="button" @class(['nav-link', 'active' => $this->panes === '4']) role="tab" data-bs-toggle="tab"
                    data-bs-target="#navs-justified-accounts-payable" aria-controls="navs-justified-accounts-payable"
                    aria-selected="false">
                    <i class="bi bi-4-circle-fill me-1_5"></i> Accounts Payable
                </button>
            </li>
            @if ($this->panes >= 5)
                <li x-on:click="$wire.panes = '5'" wire:ignore class="nav-item mb-1 mb-sm-0">
                    <button type="button" @class(['nav-link', 'active' => $this->panes === '5']) role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-justified-vote-control" aria-controls="navs-justified-vote-control"
                        aria-selected="false">
                        <i class="bi bi-5-circle-fill me-1_5"></i> Vote Control
                    </button>
                </li>
                @if ($this->panes >= 6)
                    <li x-on:click="$wire.panes = '6'" wire:ignore class="nav-item mb-1 mb-sm-0">
                        <button type="button" @class(['nav-link', 'active' => $this->panes === '6']) role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-justified-check-room" aria-controls="navs-justified-check-room"
                            aria-selected="false">
                            <i class="bi bi-6-circle-fill me-1_5"></i> Check Staff
                        </button>
                    </li>

                    @if ($this->panes >= 7)
                        <li x-on:click="$wire.panes = '7'" wire:ignore class="nav-item mb-1 mb-sm-0">
                            <button type="button" @class(['nav-link', 'active' => $this->panes === '7']) role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-justified-cheque-processing"
                                aria-controls="navs-justified-cheque-processing" aria-selected="false">
                                <i class="bi bi-7-circle-fill me-1_5"></i> Cheque Processing
                            </button>
                        </li>
                    @endif
                @endif

            @endif
        @endif
    </ul>
    <div wire:ignore.self class="tab-content">
        <div @class(['tab-pane fade', 'show active' => $this->panes === '1']) id="navs-justified-procurement-1" role="tabpanel">

            <div>

                {{-- 🔹 Row 1: Requisition Number & Requesting Unit --}}
                <div class="row mt-8">
                    <div class="col-md-6">
                        <label><strong>Requisition Number:</strong> {{ $this->requisition_no }}</label>
                    </div>
                    <div class="col-md-6">
                        <label><strong>Requesting Unit:</strong> {{ $this->requisition->department->name }}</label>
                    </div>
                </div>

                {{-- 🔹 Row 2: File Number / Form & Item --}}
                <div class="row mt-6">
                    <div class="col">
                        <label><strong>File Number / Form:</strong> {{ $this->file_no }}</label>
                    </div>
                    <div class="col">
                        <label><strong>Item:</strong> {{ $this->item }}</label>
                    </div>
                </div>

                {{-- 🔹 Row 3: Source of Funds & Date Received by Procurement --}}
                <div class="row mt-6">
                    <div class="col-md-6">
                        <label><strong>Source of Funds:</strong> {{ $this->source_of_funds }}</label>
                    </div>
                    <div class="col-md-6">
                        <label><strong>Date Received by Procurement:</strong>
                            {{ $this->getFormattedDate($this->requisition->date_received_procurement) }}</label>
                    </div>
                </div>

                {{-- 🔹 Row 4: Assigned To & Date Assigned to Officer --}}
                <div class="row mt-6">
                    <div class="col">
                        <label><strong>Assigned To:</strong>
                            {{ $this->requisition->procurement_officer->name ?? 'Not Assigned' }}</label>
                    </div>
                    <div class="col">
                        <label><strong>Date Assigned to Officer:</strong>
                            {{ $this->getFormattedDate($this->date_assigned) }}</label>
                    </div>
                </div>

                {{-- 🔹 Row 6: Date Sent to AOV Procurement --}}
                <div class="row mt-6">
                    <div class="col-md-6">
                        <label><strong>Date Sent to AOV Procurement:</strong>
                            {{ $this->getFormattedDate($this->requisition->date_sent_aov_procurement) }}</label>
                    </div>
                </div>

                {{-- 🔹 Row 7: Site Visit & Site Visit Date --}}
                <div class="row mt-4 align-items-center">
                    <div class="col-md-6">
                        <label><strong>Site Visit Required:</strong>
                            @if ($this->requisition->site_visit)
                                <i class="fa-solid fa-check text-success"></i>
                            @else
                                <i class="fa-solid fa-xmark text-danger"></i>
                            @endif
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label><strong>Site Visit Date:</strong>
                            {{ $this->getFormattedDate($this->requisition->site_visit_date) }}</label>
                    </div>
                </div>

                {{-- 🔹 Row 8: Note to PS & Note to PS Date --}}
                <div class="row mt-4 align-items-center">
                    <div class="col-md-6">
                        <label><strong>Note to PS:</strong>
                            @if ($this->requisition->note_to_ps)
                                <i class="fa-solid fa-check text-success"></i>
                            @else
                                <i class="fa-solid fa-xmark text-danger"></i>
                            @endif
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label><strong>Note to PS Date:</strong>
                            {{ $this->getFormattedDate($this->requisition->note_to_ps_date) }}</label>
                    </div>
                </div>

                {{-- 🔹 Row 9: Tender Issue & Deadline Dates --}}
                <div class="row mt-6">
                    <div class="col">
                        <label><strong>Tender/RFQ Issue Date:</strong>
                            {{ $this->getFormattedDate($this->requisition->tender_issue_date) }}</label>
                    </div>
                    <div class="col">
                        <label><strong>Tender/RFQ Deadline Date:</strong>
                            {{ $this->getFormattedDate($this->requisition->tender_deadline_date) }}</label>
                    </div>
                </div>

                {{-- 🔹 Row 10: Evaluation Start & End Dates --}}
                <div class="row mt-6">
                    <div class="col">
                        <label><strong>Evaluation Start Date:</strong>
                            {{ $this->getFormattedDate($this->requisition->evaluation_start_date) }}</label>
                    </div>
                    <div class="col">
                        <label><strong>Evaluation End Date:</strong>
                            {{ $this->getFormattedDate($this->requisition->evaluation_end_date) }}</label>
                    </div>
                </div>

                {{-- 🔹 Row 11: Date sent to DPS & PS Approval --}}
                <div class="row mt-6">
                    <div class="col">
                        <label><strong>Date sent to DPS:</strong>
                            {{ $this->getFormattedDate($this->requisition->date_sent_dps) }}</label>
                    </div>
                    <div class="col">
                        <label><strong>PS Approval:</strong>
                            @if ($this->ps_approval == 'Pending')
                                <span class="badge rounded-pill bg-danger fs-6">Pending</span>
                            @else
                                {{ $this->ps_approval }}
                            @endif
                        </label>
                    </div>
                </div>

                @if ($this->ps_approval == 'Approved')

                    @foreach ($this->requisition->vendors as $index => $vendor)
                        <div class="row mt-7">

                            <div class="col mx-5">
                                <label><strong>Vendor #{{ $index + 1 }}:</strong>
                                    {{ $vendor->vendor_name }}</label>
                            </div>

                            <div class="col mx-5">
                                <label><strong>Amount:</strong> {{ $vendor->amount }}</label>
                            </div>

                        </div>
                    @endforeach

                    @if (count($this->requisition->vendors) > 0)
                        <div class="row mt-7">

                            <div class="col mx-5">
                                <label><strong>Total:</strong> ${{ $this->total }}</label>
                            </div>
                        </div>
                    @endif
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

        <div wire:ignore.self @class(['tab-pane fade', 'show active' => $this->panes === '2']) id="navs-justified-cost_budgeting" role="tabpanel">
            <div>


                @foreach ($vendors as $vendor)
                    <div class="divider">
                        <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}</div>
                    </div>

                    <div class="row mt-8">

                        <div class="col mx-5">
                            <label><strong>Date Request Sent to Ministry of Finance:
                                </strong>{{ $this->getFormattedDate($vendor['date_sent_request_mof']) }}</label>
                        </div>

                        <div class="col mx-5">
                        </div>
                    </div>

                    <div class="row mt-7">

                        <div class="col mx-5">
                            <label><strong>Request Category:</strong>
                                {{ $vendor['request_category'] }}</label>
                        </div>

                        <div class="col mx-5">
                            <label><strong>Request Number:</strong> {{ $vendor['request_no'] }}</label>
                        </div>
                    </div>

                    <div class="row mt-7">

                        <div class="col mx-5">
                            <label><strong>Release Type:</strong> {{ $vendor['release_type'] }}</label>
                        </div>

                        <div class="col mx-5">
                            <label><strong>Release Number:</strong> {{ $vendor['release_no'] }}</label>
                        </div>
                    </div>

                    <div class="row mt-7">

                        <div class="col mx-5">
                            <label><strong>Release Date:</strong>
                                {{ $this->getFormattedDate($vendor['release_date']) }}
                        </div>

                        <div class="col mx-5">
                            <label><strong>Change of Vote Number:</strong>
                                {{ $vendor['change_of_vote_no'] }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div wire:ignore.self @class(['tab-pane fade', 'show active' => $this->panes === '3']) id="navs-justified-procurement-2" role="tabpanel">


            <div>

                @foreach ($vendors as $vendor)
                    <div class="row">
                        <div class="divider">
                            <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}
                            </div>
                        </div>

                        <div class="row mt-8">

                            <div class="col mx-5">
                                <label><strong>Purchase Order Number:
                                    </strong>{{ $vendor['purchase_order_no'] }}</label>
                            </div>

                            <div class="col mx-5">
                                <label><strong>ETA:</strong>
                                    {{ $this->getFormattedDate($vendor['eta']) }} </label>
                            </div>
                        </div>

                        <div class="row mt-7">

                            <div class="col mx-5">
                                <label><strong>Date Sent for Commitment:</strong>
                                    {{ $this->getFormattedDate($vendor['date_sent_commit']) }}</label>
                            </div>

                            <div class="col mx-5">
                                <label><strong>Date Sent to AP:</strong>
                                    {{ $this->getFormattedDate($vendor['date_sent_ap']) }}</label>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>

        <div @class(['tab-pane fade', 'show active' => $this->panes === '4']) id="navs-justified-accounts-payable" role="tabpanel">

            <div>

                @foreach ($vendors as $vendor)
                    <div class="row mt-2">
                        <div class="divider">
                            <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}</div>
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

            </div>
        </div>

        <div @class(['tab-pane fade', 'show active' => $this->panes === '5']) id="navs-justified-vote-control" role="tabpanel">

            <div>
                @foreach ($vendors as $vendor)
                    <div class="row mt-2">
                        <div class="divider">
                            <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}</div>
                        </div>

                        <div class="row mt-2">

                            <div class="col mx-5">
                                <label><strong>Batch Number:</strong> {{ $vendor['batch_no'] }}</label>
                            </div>

                            <div class="col mx-5">
                                <label><strong>Voucher Number: </strong>{{ $vendor['voucher_no'] }}</label>
                            </div>
                        </div>

                        <div class="row mt-7">

                            <div class="col mx-5">
                                <label><strong>Date Sent to Check Staff:</strong>
                                    {{ $this->getFormattedDate($vendor['date_sent_checkstaff']) }}</label>
                            </div>

                            <div class="col mx-5">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div @class(['tab-pane fade', 'show active' => $this->panes === '6']) id="navs-justified-check-room" role="tabpanel">


            <div>


                @foreach ($vendors as $vendor)
                    <div class="row">
                        <div class="divider">
                            <div class="divider-text fw-bold fs-5">{{ $vendor['vendor_name'] }}</div>
                        </div>

                        <div class="row mt-2">

                            <div class="col mx-5">
                                <label><strong>Date Voucher Received from Vote Control:</strong>
                                    {{ $this->getFormattedDate($vendor['date_received_from_vc']) }}</label>
                            </div>

                            <div class="col mx-5">
                                <label><strong>Voucher Sent To:
                                    </strong>{{ $vendor['voucher_destination'] }}</label>
                            </div>
                        </div>

                        @if ($vendor['voucher_destination'] == 'Internal Audit')
                            <div class="row mt-8">

                                <div class="col mx-5">
                                    <label><strong>Date Sent to Audit:</strong>
                                        {{ $this->getFormattedDate($vendor['date_sent_audit']) }}</label>
                                </div>

                                <div class="col mx-5">
                                    <label><strong>Date Received from Audit:
                                        </strong>{{ $this->getFormattedDate($vendor['date_received_from_audit']) }}</label>
                                </div>
                            </div>
                        @endif

                        <div class="row mt-8">

                            <div class="col mx-5">
                                <label><strong>Date Voucher Sent to Cheque Processing:</strong>
                                    {{ $this->getFormattedDate($vendor['date_sent_chequeprocessing']) }}</label>
                            </div>

                            <div class="col mx-5">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div @class(['tab-pane fade', 'show active' => $this->panes === '7']) id="navs-justified-cheque-processing" role="tabpanel">

            <div>
                <div class="row mt-8">

                    <div class="col mx-5">
                        <label><strong>Date Cheque Processed:</strong>
                            {{ $this->getFormattedDate($this->requisition->date_cheque_processed) }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Cheque Number: </strong>{{ $this->requisition->cheque_no }}</label>
                    </div>
                </div>

                <div class="row mt-8">

                    <div class="col mx-5">
                        <label><strong>Cheque Date:</strong>
                            {{ $this->getFormattedDate($this->requisition->date_of_cheque) }}</label>
                    </div>

                    <div class="col mx-5">
                        <label><strong>Date Cheque Sent to Cheque Dispatch:
                            </strong>{{ $this->getFormattedDate($this->requisition->date_sent_dispatch) }}</label>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
