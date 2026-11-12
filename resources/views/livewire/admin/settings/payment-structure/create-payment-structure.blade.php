<div>
    <div>
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Create Payment Structure</h3>
            </div>
            <div class="card-body">

                @include('components.alerts.response-alerts')

                <form wire:submit.prevent="submit">
                    {{-- Item + Delivery Type --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Item <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="item_id" style="width: 100%;">
                                    <option value="">-- Select Item --</option>
                                    @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('item_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Delivery Type <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="delivery_type" style="width: 100%;">
                                    <option value="direct">Agent to agent</option>
                                    <option value="warehouse_split">Warehouse</option>
                                </select>
                                @error('delivery_type')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Tax --}}
                    {{--<div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>VAT Tax (%)</label>
                                <input type="number" step="0.01" min="0" class="form-control"
                                    wire:model="tax_percentage" placeholder="0.00">
                                @error('tax_percentage')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>--}}

                    {{-- Pick up / Drop off --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pick up / Drop Off Partner Type <span class="text-danger">*</span></label>
                                <select class="form-control" wire:model="pick_up_drop_off_partner_type" style="width: 100%;">
                                    <option value="fixed">Fixed Amount</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                                @error('pick_up_drop_off_partner_type')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>
                                    Pick up / Drop Off Partner
                                    {{ $pick_up_drop_off_partner_type === 'percentage' ? '(%)' : 'Amount' }}
                                </label>
                                <input type="number" step="0.01" min="0" class="form-control"
                                    wire:model="pick_up_drop_off_partner_amount" placeholder="0.00">
                                @error('pick_up_drop_off_partner_amount')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Percentages --}}
                    <div class="row">
                        {{--<div class="col-md-4">
                            <div class="form-group">
                                <label>Transport Partner Percentage (%)</label>
                                <input type="number" step="0.01" min="0" class="form-control"
                                    wire:model="transport_partner_percentage" placeholder="0.00">
                                @error('transport_partner_percentage')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>--}}

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Platform Percentage (%)</label>
                                <input type="number" step="0.01" min="0" class="form-control"
                                    wire:model="platform_percentage" placeholder="0.00">
                                @error('platform_percentage')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Platform Fixed Amount</label>
                                <input type="number" step="0.01" min="0" class="form-control"
                                    wire:model="platform_fixed_amount" placeholder="0.00">
                                @error('platform_fixed_amount')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
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
                <a href="{{ route('admin.payment-structure.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </div>
</div>