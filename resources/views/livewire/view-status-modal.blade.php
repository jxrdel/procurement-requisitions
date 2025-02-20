<!-- Modal -->
<div wire:ignore.self class="modal fade" id="viewStatusModal" tabindex="-1" aria-labelledby="viewStatusModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewStatusModalLabel" style="color: black; text-align:center">
                    {{ $this->requisition->requisition_no ?? '' }} Status
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: black">
                <h5 class="mt-5">Vendors ({{ count($vendors) }})</h5>
                
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Vendor</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vendors as $vendor)
                            <tr>
                                <td>{{ $vendor->vendor_name }}</td>
                                <td>${{ number_format($vendor->amount, 2) }}</td>
                                <td>{{ $vendor->vendor_status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="4">No Vendors</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="modal-footer" style="align-items: center">
                <div style="margin:auto">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
