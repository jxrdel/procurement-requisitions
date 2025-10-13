<div wire:ignore.self class="modal fade" id="acceptRequisitionForm" tabindex="-1"
    aria-labelledby="acceptRequisitionFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                {{-- Centered and smaller title --}}
                <h1 class="modal-title fs-5 w-100 text-center fw-bold" id="acceptRequisitionFormLabel"
                    style="color: black;">
                    Accept Requisition
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center" style="color: black">

                <form wire:submit.prevent="acceptRequisition" action="">

                    <div class="row">
                        <div class="col-12">
                            <p class="mb-3">Select the recipient of the approved requisition.</p>

                            <div wire:ignore class="mx-auto" style="width: 100%;">
                                <select wire:model="selectedOfficer" class="form-select form-select-lg"
                                    id="officerSelect">
                                    <option value="" selected>Select Officer</option>
                                    <option value="Permanent Secretary">Permanent Secretary</option>
                                    <option value="Deputy Permanent Secretary">Deputy Permanent Secretary</option>
                                    <option value="County Medical Officer">County Medical Officer</option>
                                </select>
                            </div>

                            @error('selectedOfficer')
                                <div class="text-danger mt-1"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>
                </form>

            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success" wire:click="acceptRequisition"
                    wire:loading.attr="disabled">
                    Accept
                </button>

                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
