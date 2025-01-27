<div>
    @include('add-log')
    @can('edit-records')
        <div class="row mt-5">
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addLogModal"
                class="btn btn-dark waves-effect waves-light w-25 m-auto">
                <span class="tf-icons ri-file-add-line me-1_5"></span>Add Log
            </a>
        </div>
    @endcan

    <div class="row mt-8">
        <table class="table table-hover table-bordered w-100">
            <thead>
                <tr>
                    <th>Details</th>
                    <th class="text-center" style="width: 20%">Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse ($logs as $index => $log)
                    <tr>
                        <td>{{ $log->details }}</td>
                        <td class="text-center">

                            <button @cannot('delete-records') disabled @endcannot
                                wire:confirm="Are you sure you want to delete this log?"
                                wire:click="deleteLog({{ $log->id }})" type="button" class="btn btn-danger">
                                <i class="ri-delete-bin-2-line me-1"></i> Delete
                            </button>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No logs added</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


@script
    <script>
        $(document).ready(function() {
            window.addEventListener('close-log-modal', event => {
                $('#addLogModal').modal('hide');
            })
        });
    </script>
@endscript
