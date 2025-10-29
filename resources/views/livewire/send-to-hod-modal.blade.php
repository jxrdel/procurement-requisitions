<div wire:ignore.self class="modal fade" id="sendToHodModal" tabindex="-1" aria-labelledby="sendToHodModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form wire:submit.prevent="sendToHOD" action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 w-100 text-center fw-bold" id="sendToHodModalLabel"
                        style="color: black;">
                        Send To Head of Department
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body text-center" style="color: black">


                    <div class="row">
                        <div class="col-12">
                            <p class="mb-3">Please enter any necessary notes for the Head of Department.</p>

                            <div class="form-floating form-floating-outline mb-3">
                                {{-- Textarea for contact_person_note --}}
                                <textarea required wire:model="contact_person_note"
                                    class="form-control @error('contact_person_note')is-invalid @enderror" placeholder="Please enter a note"
                                    id="contactPersonNoteInput" style="height: 150px"></textarea>
                                <label for="contactPersonNoteInput">Minute to HOD</label>
                            </div>

                            @error('contact_person_note')
                                <div class="text-danger mt-1"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="modal-footer justify-content-center">
                    {{-- <button type="button" class="btn btn-primary" wire:click="sendToHOD" wire:loading.attr="disabled">
                    Send
                </button> --}}
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="sendToHOD">
                        <i class="tf-icons ri-mail-send-line me-1_5"></i>
                        <span>Send</span>
                        <div wire:loading wire:target="sendToHOD"
                            class="spinner-border spinner-border-sm text-white mx-1" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>

                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                </div>
        </form>
    </div>
</div>
</div>
