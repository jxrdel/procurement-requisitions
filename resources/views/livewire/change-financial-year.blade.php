<!-- Modal -->
<div wire:ignore.self class="modal fade" id="editFYModal" tabindex="-1" aria-labelledby="editFYModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center" id="editFYModalLabel" style="color: black; text-align:center">
                    Change Financial Year</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="save" action="">
                <div class="modal-body" style="color: black">
                    @error('financial_year')
                        <div class="alert alert-danger text-center">{{ $message }}</div>
                    @enderror

                    <div class="row justify-content-center" style="margin-top: 10px;">
                        <div class="col-auto">
                            <div class="d-flex align-items-center justify-content-center">
                                <!-- Minus Button -->
                                <button wire:click="decrement" class="btn btn-outline-secondary me-2" type="button"
                                    id="minusButton">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <!-- Input Field -->
                                <div class="form-floating">
                                    <input required wire:model="financial_year" type="text"
                                        class="form-control text-center @error('financial_year')is-invalid @enderror"
                                        autocomplete="off" id="detailsInput" placeholder="Financial Year"
                                        aria-describedby="detailsInputHelp" />
                                </div>
                                <!-- Plus Button -->
                                <button wire:click="increment" class="btn btn-outline-secondary ms-2" type="button"
                                    id="plusButton">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer" style="align-items: center">
                    <div style="margin:auto">
                        <button class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
