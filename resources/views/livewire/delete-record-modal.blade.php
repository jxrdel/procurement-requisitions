<!-- Modal -->
<div wire:ignore.self class="modal fade" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <form wire:submit.prevent="deleteRecord" action="">
            <div class="modal-body" style="color: black">
                <div class="row">

                    <strong style="text-align: center">Are you sure you want to delete this record?</strong>

                </div>
                    

            </div>
            <div class="modal-footer" style="align-items: center">
                <div style="margin:auto">
                    <button class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
      </div>
    </div>
</div>
