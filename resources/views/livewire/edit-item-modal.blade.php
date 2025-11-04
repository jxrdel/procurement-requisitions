<div wire:ignore.self class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center" id="editItemModalLabel" style="color: black; text-align:center">
                    Edit Item
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: black">

                {{-- Form for adding item details --}}
                <form wire:submit.prevent="editItem" action="">

                    {{-- Row 1: Item Name (Full Width) --}}
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-floating form-floating-outline">
                                <input required wire:model="item_name" type="text"
                                    class="form-control @error('item_name')is-invalid @enderror" autocomplete="off"
                                    id="itemInput" placeholder="Item Name" aria-describedby="itemInputHelp" />
                                <label for="itemInput">Item <span class="text-danger">*</span></label>
                            </div>
                            @error('item_name')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 2: Quantities and Unit --}}
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-floating form-floating-outline">
                                <input required wire:model="qty_in_stock" type="number"
                                    class="form-control @error('qty_in_stock')is-invalid @enderror" autocomplete="off"
                                    id="qtyInStockInput" placeholder="Quantity in Stock" />
                                <label for="qtyInStockInput">Qty in Stock <span class="text-danger">*</span></label>
                            </div>
                            @error('qty_in_stock')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col">
                            <div class="form-floating form-floating-outline">
                                <input required wire:model="qty_requesting" type="number"
                                    class="form-control @error('qty_requesting')is-invalid @enderror" autocomplete="off"
                                    id="qtyRequestingInput" placeholder="Quantity Requesting" />
                                <label for="qtyRequestingInput">Qty Requesting <span
                                        class="text-danger">*</span></label>
                            </div>
                            @error('qty_requesting')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col">
                            <div class="form-floating form-floating-outline">
                                <input wire:model="unit_of_measure" type="text"
                                    class="form-control @error('unit_of_measure')is-invalid @enderror"
                                    autocomplete="off" id="unitOfMeasureInput" placeholder="Unit of Measure" />
                                <label for="unitOfMeasureInput">Unit of Measure</label>
                            </div>
                            @error('unit_of_measure')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 3: Size, Colour, Brand/Model --}}
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-floating form-floating-outline">
                                <input wire:model="size" type="text"
                                    class="form-control @error('size')is-invalid @enderror" autocomplete="off"
                                    id="sizeInput" placeholder="Size (Optional)" />
                                <label for="sizeInput">Size</label>
                            </div>
                            @error('size')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col">
                            <div class="form-floating form-floating-outline">
                                <input wire:model="colour" type="text"
                                    class="form-control @error('colour')is-invalid @enderror" autocomplete="off"
                                    id="colourInput" placeholder="Colour (Optional)" />
                                <label for="colourInput">Colour</label>
                            </div>
                            @error('colour')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>

                        <div class="col">
                            <div class="form-floating form-floating-outline">
                                <input wire:model="brand_model" type="text"
                                    class="form-control @error('brand_model')is-invalid @enderror" autocomplete="off"
                                    id="brandModelInput" placeholder="Brand/Model (Optional)" />
                                <label for="brandModelInput">Brand / Model (If Applicable)</label>
                            </div>
                            @error('brand_model')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 4: Other Details (Full Width) --}}
                    <div class="row mb-4">
                        <div class="col">
                            <div class="form-floating form-floating-outline">
                                <input wire:model="other" type="text"
                                    class="form-control @error('other')is-invalid @enderror" autocomplete="off"
                                    id="otherInput" placeholder="Other Details (Optional)" />
                                <label for="otherInput">Other Details</label>
                            </div>
                            @error('other')
                                <div class="text-danger"> {{ $message }} </div>
                            @enderror
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
