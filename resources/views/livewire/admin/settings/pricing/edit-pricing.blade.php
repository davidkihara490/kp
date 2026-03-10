<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">Edit Pricing</h3>
        </div>
        <div class="card-body">
            @include('components.alerts.response-alerts')

            <form wire:submit.prevent="submit">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Select Item <span class="text-danger">*</span></label>
                            <select class="form-control" wire:model="selected_item_id" style="width: 100%;">
                                <option value="">Select Item</option>
                                @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('selected_item_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label>Select Weight Range <span class="text-danger">*</span></label>
                            <select class="form-control" wire:model="selected_weight_range_id" style="width: 100%;">
                                <option value="">Select Weight Range</option>
                                @foreach ($weightRanges as $range)
                                <option value="{{ $range->id }}">
                                    {{ $range->min_weight }} - {{ $range->max_weight }} {{ $range->unit ?? 'kg' }}
                                </option>
                                @endforeach
                            </select>
                            @error('selected_weight_range_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                @foreach($pricing_rows as $index => $row)
                <div class="row mt-3 pricing-row" wire:key="pricing-row-{{ $index }}">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Source Zone @if($loop->first)<span class="text-danger">*</span>@endif</label>
                            <select class="form-control"
                                wire:model="pricing_rows.{{ $index }}.source_zone_id"
                                style="width: 100%;">
                                <option value="">Select Source Zone</option>
                                @foreach ($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                            @error('pricing_rows.' . $index . '.source_zone_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Destination Zone @if($loop->first)<span class="text-danger">*</span>@endif</label>
                            <select class="form-control"
                                wire:model="pricing_rows.{{ $index }}.destination_zone_id"
                                style="width: 100%;">
                                <option value="">Select Destination Zone</option>
                                @foreach ($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                            @error('pricing_rows.' . $index . '.destination_zone_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Cost @if($loop->first)<span class="text-danger">*</span>@endif</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number"
                                    step="0.01"
                                    min="0"
                                    class="form-control"
                                    wire:model="pricing_rows.{{ $index }}.cost"
                                    placeholder="0.00">
                            </div>
                            @error('pricing_rows.' . $index . '.cost')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button"
                                class="btn btn-danger d-block w-100"
                                wire:click="removePricingRow({{ $index }})"
                                @if(count($pricing_rows) <=1) disabled @endif>
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach

                @error('pricing_rows')
                <div class="row">
                    <div class="col-12">
                        <span class="text-danger">{{ $message }}</span>
                    </div>
                </div>
                @enderror

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="font-weight-bold mb-0">Zone Pricing</label>
                            <button type="button" class="btn btn-sm btn-success" wire:click="addPricingRow">
                                <i class="fas fa-plus"></i> Add Row
                            </button>
                        </div>
                        <hr class="mt-2">
                    </div>
                </div>

            </form>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary" wire:click="submit" wire:loading.attr="disabled"
                wire:target="submit">
                <span wire:loading.remove wire:target="submit">Submit</span>
                <span wire:loading wire:target="submit">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Submitting...
                </span>
            </button>
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
        </div>
    </div>
</div>

@push('styles')
<style>
    .pricing-row {
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 1rem;
        margin-bottom: 0.5rem;
    }

    .pricing-row:last-child {
        border-bottom: none;
    }

    .pricing-row .form-group {
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .pricing-row .form-group {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush