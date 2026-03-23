<div>
    <div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pricing Management</h3>
            <div class="card-tools">
                <button class="btn btn-sm btn-success" wire:click="createPricing">
                    <i class="fas fa-plus"></i> Add New Pricing
                </button>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search by item name..." 
                           wire:model.live.debounce.300ms="search">
                </div>
                <div class="col-md-3">
                    <select class="form-control" wire:model.live="itemFilter">
                        <option value="">All Items</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" wire:model.live="zoneFilter">
                        <option value="">All Zones</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary btn-block" wire:click="resetFilters">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </div>
            
            <!-- Pricing Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Category</th>
                            <th>Weight Range (kg)</th>
                            <th>Zones Covered</th>
                            <th>Price Entries</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pricings as $pricing)
                            <tr>
                                <td>{{ $pricing->id }}</td>
                                <td>
                                    <strong>{{ $pricing->item->name ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $pricing->min_weight }} - {{ $pricing->max_weight }} kg
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $uniqueZones = collect();
                                        foreach($pricing->items as $item) {
                                            $uniqueZones->push($item->source_zone_id);
                                            $uniqueZones->push($item->destination_zone_id);
                                        }
                                        $zoneCount = $uniqueZones->unique()->count();
                                    @endphp
                                    <span class="badge badge-primary">{{ $zoneCount }} zones</span>
                                </td>
                                <td>
                                    <span class="badge badge-success">{{ $pricing->items->count() }} prices</span>
                                </td>
                                <td>{{ $pricing->created_at->format('d M Y') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" wire:click="viewPricing({{ $pricing->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" wire:click="editPricing({{ $pricing->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $pricing->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No pricing records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $pricings->links() }}
            </div>
        </div>
    </div>
    
    <!-- View Pricing Modal -->
    <div class="modal fade" id="viewPricingModal" tabindex="-1" data-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-eye"></i> Pricing Details
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($selectedPricing)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Item:</strong> {{ $selectedPricing->item->name }}
                            </div>
                            <div class="col-md-6">
                                <strong>Weight Range:</strong> {{ $selectedPricing->min_weight }} - {{ $selectedPricing->max_weight }} kg
                            </div>
                        </div>
                        
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-sm table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Source Zone</th>
                                        <th>Destination Zone</th>
                                        <th class="text-right">Cost (KES)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pricingItems as $sourceId => $data)
                                        @foreach($data['destinations'] as $destId => $destData)
                                            <tr>
                                                <td>{{ $data['zone']->name }}</td>
                                                <td>{{ $destData['zone']->name }}</td>
                                                <td class="text-right">
                                                    <span class="badge badge-success badge-lg">
                                                        KES {{ number_format($destData['cost'], 2) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No pricing items found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit/Create Pricing Modal -->
    <div class="modal fade" id="editPricingModal" tabindex="-1" data-backdrop="static" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header {{ $editId ? 'bg-warning' : 'bg-success' }} text-white">
                    <h5 class="modal-title">
                        <i class="fas {{ $editId ? 'fa-edit' : 'fa-plus' }}"></i>
                        {{ $editId ? 'Edit Pricing' : 'Create New Pricing' }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="updatePricing">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item Category <span class="text-danger">*</span></label>
                                    <select class="form-control @error('editItemId') is-invalid @enderror" 
                                            wire:model="editItemId">
                                        <option value="">Select Item</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('editItemId')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Min Weight (kg) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control @error('editMinWeight') is-invalid @enderror" 
                                           wire:model="editMinWeight">
                                    @error('editMinWeight')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Max Weight (kg) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control @error('editMaxWeight') is-invalid @enderror" 
                                           wire:model="editMaxWeight">
                                    @error('editMaxWeight')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-2">
                            <i class="fas fa-info-circle"></i>
                            Enter prices for each source-destination zone combination. Leave empty for no pricing.
                        </div>
                        
                        <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                            <table class="table table-bordered table-sm table-hover">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th style="min-width: 150px; background-color: #f8f9fa;">Source Zone →</th>
                                        @foreach($zones as $zone)
                                            <th class="text-center" style="min-width: 120px; background-color: #f8f9fa;">
                                                {{ $zone->name }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($zones as $sourceZone)
                                        <tr>
                                            <th class="bg-light">{{ $sourceZone->name }}</th>
                                            @foreach($zones as $destZone)
                                                <td class="text-center" style="vertical-align: middle;">
                                                    @if($sourceZone->id == $destZone->id)
                                                        <span class="badge badge-secondary">Local</span>
                                                    @else
                                                        @php
                                                            $priceValue = $prices[$sourceZone->id][$destZone->id] ?? null;
                                                        @endphp
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">KES</span>
                                                            </div>
                                                            <input type="number" 
                                                                   class="form-control form-control-sm text-right price-input"
                                                                   wire:model="prices.{{ $sourceZone->id }}.{{ $destZone->id }}"
                                                                   step="10"
                                                                   min="0"
                                                                   placeholder="Enter price"
                                                                   value="{{ $priceValue }}">
                                                        </div>
                                                        @if($priceValue && $priceValue > 0)
                                                            <small class="text-success">
                                                                <i class="fas fa-check-circle"></i> {{ number_format($priceValue, 2) }}
                                                            </small>
                                                        @endif
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3 text-muted">
                            <small>
                                <i class="fas fa-info-circle"></i> 
                                <strong>Tip:</strong> Prices are in Kenyan Shillings (KES). Leave empty for routes not serviced.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ $editId ? 'Update Pricing' : 'Create Pricing' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" data-backdrop="static" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this pricing record?</p>
                    <p class="text-danger"><small>This action cannot be undone and will remove all associated pricing items.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        // Show modals when events are dispatched
        Livewire.on('show-view-modal', () => {
            $('#viewPricingModal').modal('show');
        });
        
        Livewire.on('show-edit-modal', () => {
            $('#editPricingModal').modal('show');
        });
        
        Livewire.on('show-delete-modal', () => {
            $('#deleteModal').modal('show');
        });
        
        Livewire.on('pricing-updated', () => {
            $('#editPricingModal').modal('hide');
        });
        
        Livewire.on('pricing-deleted', () => {
            $('#deleteModal').modal('hide');
        });
        
        // Optional: Add visual feedback when price inputs change
        document.addEventListener('input', function(e) {
            if (e.target.classList && e.target.classList.contains('price-input')) {
                let parent = e.target.closest('td');
                if (parent) {
                    let existingMsg = parent.querySelector('.text-success');
                    if (!existingMsg && e.target.value && e.target.value > 0) {
                        let msg = document.createElement('small');
                        msg.className = 'text-success d-block mt-1';
                        msg.innerHTML = '<i class="fas fa-check-circle"></i> ' + 
                            new Intl.NumberFormat().format(e.target.value);
                        parent.appendChild(msg);
                        setTimeout(() => msg.remove(), 1500);
                    }
                }
            }
        });
    });
</script>
@endpush
</div>