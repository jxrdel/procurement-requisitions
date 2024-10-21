
<!-- Modal -->
<div wire:ignore.self class="modal fade" id="addLogModal" tabindex="-1" aria-labelledby="addLogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <h1 class="modal-title fs-5" id="addLogModalLabel" style="color: black; text-align:center">Add Log</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form wire:submit.prevent="addLog" action="">
            <div class="modal-body" style="color: black">
                
                <div class="row" style="margin-top:10px">

                    <div class="form-floating form-floating-outline">
                        <input
                        required
                        wire:model="logdetails"
                        type="text"
                        class="form-control"
                        autocomplete="off"
                        id="detailsInput"
                        placeholder="Details"
                        aria-describedby="detailsInputHelp" />
                        <label for="detailsInput">Details</label>
                    </div>


                </div>

            </div>
            <div class="modal-footer" style="align-items: center">
                <div style="margin:auto">
                    <button class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>