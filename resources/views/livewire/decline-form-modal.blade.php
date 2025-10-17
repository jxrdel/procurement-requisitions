<div wire:ignore.self class="modal fade" id="declineRequisitionForm" tabindex="-1"
    aria-labelledby="declineRequisitionFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 w-100 text-center fw-bold" id="declineRequisitionFormLabel"
                    style="color: black;">
                    Decline Requisition
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center" style="color: black">

                <form wire:submit.prevent="declineRequisition" action="">

                    <div class="row">
                        <div class="col-12">
                            <p class="mb-3">Please state the reason for declining this requisition.</p>

                            <div class="form-floating form-floating-outline mb-3">
                                {{-- Textarea for decline reason --}}
                                <textarea required wire:model="declineReason" class="form-control @error('declineReason')is-invalid @enderror"
                                    placeholder="State the issues here" id="declineReasonInput" style="height: 150px"></textarea>
                                <label for="declineReasonInput">Issues</label>
                            </div>

                            @error('declineReason')
                                <div class="text-danger mt-1"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                </form>

            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" wire:click="declineRequisition"
                    wire:loading.attr="disabled">
                    Decline
                </button>

                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
