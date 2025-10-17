<div wire:ignore.self class="modal fade" id="approveRequisitionFormHOD" tabindex="-1"
    aria-labelledby="approveRequisitionFormHODLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                {{-- Centered and smaller title --}}
                <h1 class="modal-title fs-5 w-100 text-center fw-bold" id="approveRequisitionFormHODLabel"
                    style="color: black;">
                    Approve Requisition Form
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-center" style="color: black">

                <form wire:submit.prevent="acceptRequisition" action="">

                    <div class="row">
                        <div class="col-12">
                            <p class="mb-3">Select the recipient of the approved requisition.</p>

                            <div wire:ignore class="mx-auto" style="width: 100%;">
                                <select required wire:model="selectedOfficer"
                                    class="form-select form-select-lg @error('selectedOfficer')is-invalid @enderror"
                                    id="officerSelect">
                                    <option value="" selected>Select Officer</option>
                                    @foreach ($reportingOfficers as $officer)
                                        <option value="{{ $officer->id }}">{{ $officer->reporting_officer_role }} |
                                            {{ $officer->name }}</option>
                                    @endforeach
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
                <button type="button" class="btn btn-success" wire:click="approveRequisitionHOD"
                    wire:loading.attr="disabled">
                    Approve
                </button>

                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
