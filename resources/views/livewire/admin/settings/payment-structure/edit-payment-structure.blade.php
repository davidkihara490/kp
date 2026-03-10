    <div>
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">Edit Payment Structure</h3>
            </div>
            <div class="card-body">

                @include('components.alerts.response-alerts')

                <form wire:submit.prevent="submit">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Delivery Type</label>
                                <select class="form-control" wire:model="delivery_type" style="width: 100%;">
                                    <option value="direct">Agent to agent</option>
                                    <option value="warehouse_split">Warehouse</option>
                                </select>
                                @error('delivery_type')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tax(%)</label>
                                <input type="text" class="form-control" wire:model="tax_percentage" placeholder="" min="0">
                                @error('tax_percentage')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pick up/ Drop Off Partner Percentage</label>
                                <input type="text" class="form-control" wire:model="pick_up_drop_off_partner_percentage" placeholder="" min="0">
                                @error('pick_up_drop_off_partner_percentage')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Transport Partner Percentage</label>
                                <input type="text" class="form-control" wire:model="transport_partner_percentage" placeholder="" min="0">
                                @error('transport_partner_percentage')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Platform Percentage</label>
                                <input type="text" class="form-control" wire:model="platform_percentage" placeholder="" min="0">
                                @error('platform_percentage')
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
                <button type="button" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </div>
