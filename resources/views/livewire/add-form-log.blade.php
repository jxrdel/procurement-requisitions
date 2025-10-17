<!-- Add Log Modal -->
<div wire:ignore.self class="modal fade" id="addLogModal" tabindex="-1" aria-labelledby="addLogModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center" id="addLogModalLabel" style="color: black; text-align:center">
                    Add Log
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: black">

                {{-- Form for adding log details --}}
                <form wire:submit.prevent="saveLog" action="">

                    {{-- Single Textarea for Details --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                {{-- Assuming wire:model="details" for the single text input --}}
                                <textarea required wire:model="details" class="form-control @error('details')is-invalid @enderror" id="detailsInput"
                                    placeholder="Enter log details here" style="height: 150px;"></textarea>
                                <label for="detailsInput">Details</label>
                            </div>
                            @error('details')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Modal Footer for Save/Cancel --}}
                    <div class="modal-footer" style="align-items: center">
                        <div style="margin:auto">
                            {{-- Button to submit the form and trigger saveLog --}}
                            <button type="submit" class="btn btn-primary">Save</button>

                            {{-- Cancel button to dismiss the modal --}}
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
