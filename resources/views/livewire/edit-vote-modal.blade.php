<!-- Modal -->
<div wire:ignore.self class="modal fade" id="editVoteModal" tabindex="-1" aria-labelledby="editVoteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editVoteModalLabel" style="color: black; text-align:center">Edit Vote
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="editVote" action="">
                <div class="modal-body" style="color: black">

                    <div class="row">

                        <div class="form-floating form-floating-outline">
                            <input required wire:model.blur="name" type="text" class="form-control"
                                autocomplete="off" id="nameInput" placeholder="John Doe"
                                aria-describedby="nameInputHelp" />
                            <label for="nameInput">Name</label>
                        </div>

                    </div>

                    <div class="row mt-4">
                        <div class="form-floating form-floating-outline">
                            <input required wire:model="number" type="text"
                                class="form-control @error('number')is-invalid @enderror" autocomplete="off"
                                id="numberInput" placeholder="firstname.lastname" aria-describedby="numberInputHelp" />
                            <label for="numberInput">Number</label>
                        </div>
                        @error('number')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
                    </div>

                    <div class="row mt-4">
                        <div class="col d-flex align-items-center">
                            <label class="form-check-label me-2" for="flexSwitchCheckChecked">Active Vote</label>
                            <div class="form-check form-switch" style="margin-top: 10px">
                                <input wire:model="is_active" class="form-check-input" type="checkbox"
                                    id="flexSwitchCheckChecked" />
                            </div>
                        </div>
                        @error('is_active')
                            <div class="text-danger"> {{ $message }} </div>
                        @enderror
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
