<div wire:ignore.self class="modal fade" id="forwardFormReportingOfficer" tabindex="-1"
    aria-labelledby="forwardFormReportingOfficerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                {{-- Centered and smaller title --}}
                <h1 class="modal-title fs-5 w-100 text-center fw-bold" id="forwardFormReportingOfficerLabel"
                    style="color: black;">
                    Forward Requisition Form
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center" style="color: black">

                <form wire:submit.prevent="forwardForm" action="">

                    <div class="row">
                        <div class="col-12">
                            <p class="mb-3">
                                Select the recipient and add a minute before forwarding the requisition form.
                            </p>


                            <div wire:ignore class="mx-auto" style="width: 100%;">
                                <select required wire:model="forwardedOfficer"
                                    class="form-select form-select-lg @error('forwardedOfficer')is-invalid @enderror"
                                    id="officerSelect">
                                    <option value="" selected>Select Recipient</option>
                                    @foreach ($allReportingOfficers as $officer)
                                        <option value="{{ $officer->id }}">{{ $officer->reporting_officer_role }} |
                                            {{ $officer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @error('forwardedOfficer')
                                <div class="text-danger mt-1"> {{ $message }} </div>
                            @enderror
                            <div class="form-floating form-floating-outline mt-4">
                                <textarea wire:model="reporting_officer_note" class="form-control @error('reporting_officer_note')is-invalid @enderror"
                                    placeholder="Enter minute here" id="reportingOfficerNoteInput" style="height: 100px"></textarea>
                                <label for="reportingOfficerNoteInput">Minute</label>
                            </div>

                            @error('reporting_officer_note')
                                <div class="text-danger mt-1"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                </form>

            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" wire:click="forwardForm" wire:loading.attr="disabled"
                    wire:target="forwardForm">
                    <span>Forward</span>
                    <div wire:loading wire:target="forwardForm" class="spinner-border spinner-border-sm text-white mx-1"
                        role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>

                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
