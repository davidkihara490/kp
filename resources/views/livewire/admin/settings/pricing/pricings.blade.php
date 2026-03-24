<div>
    <div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                <i class="fas fa-tags mr-2"></i>Pricings
            </h3>
            <a href="{{ route('admin.pricing.create') }}" class="btn btn-success btn-sm float-right">
                <i class="fas fa-plus mr-1"></i> New Pricing
            </a>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            @if($pricings->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    No pricing records found. Click "New Pricing" to create one.
                </div>
            @else
                <div class="accordion" id="pricingAccordion">
                    @foreach ($pricings as $index => $pricing)
                        <div class="card mb-3 pricing-card" data-pricing-id="{{ $pricing->id }}">
                            <div class="card-header pricing-header" style="background-color: #f8f9fa; cursor: pointer;" 
                                 data-toggle="collapse" 
                                 data-target="#collapse{{ $pricing->id }}" 
                                 aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                 aria-controls="collapse{{ $pricing->id }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chevron-right mr-2 collapse-icon" id="icon{{ $pricing->id }}"></i>
                                        <div>
                                            <h5 class="mb-0 font-weight-bold">
                                                <i class="fas fa-box mr-2 text-primary"></i>
                                                {{ $pricing->item->name ?? 'Unknown Item' }}
                                            </h5>
                                            <small class="text-muted text-bold">
                                                <i class="fas fa-weight-hanging mr-1"></i>
                                                Weight Range: {{ $pricing->min_weight }} - {{ $pricing->max_weight }} kg
                                            </small>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.pricing.edit', $pricing->id) }}" 
                                           class="btn btn-sm btn-primary mr-2"
                                           title="Edit Pricing">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $pricing->id }}, '{{ addslashes($pricing->item->name ?? 'Pricing') }}')"
                                                title="Delete Pricing">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div id="collapse{{ $pricing->id }}" 
                                 class="collapse {{ $loop->first ? 'show' : '' }}" 
                                 data-parent="#pricingAccordion">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mb-0 pricing-table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="width: 150px; background-color: #e9ecef;">
                                                        <i class="fas fa-map-marker-alt mr-1"></i> From \ To
                                                    </th>
                                                    @foreach ($zones as $zone)
                                                        <th class="text-center">
                                                            {{ $zone->name }}
                                                            <i class="fas fa-arrow-right ml-1 text-muted"></i>
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($zones as $source)
                                                    <tr>
                                                        <th class="bg-light" style="width: 150px;">
                                                            <i class="fas fa-location-dot mr-1 text-primary"></i>
                                                            {{ $source->name }}
                                                        </th>
                                                        @foreach ($zones as $destination)
                                                            @php
                                                                $price = $pricing->items
                                                                    ->where('source_zone_id', $source->id)
                                                                    ->where('destination_zone_id', $destination->id)
                                                                    ->first();
                                                            @endphp
                                                            <td class="text-center {{ $price ? 'has-price' : 'no-price' }}">
                                                                @if($price)
                                                                    <span class="price-value">
                                                                        <i class="fas fa-kenyan-sign mr-1 text-success"></i>
                                                                        {{ number_format($price->cost, 2) }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted">—</span>
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-secondary">
                                                <tr>
                                                    <td colspan="{{ count($zones) + 1 }}" class="text-muted small">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Prices shown in Kenyan Shillings (KES)
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    
                                    <!-- Pricing Statistics -->
                                    <div class="pricing-stats p-3 bg-light border-top">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-chart-line mr-1"></i>
                                                    Total Routes: {{ $pricing->items->count() }}
                                                </small>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-chart-bar mr-1"></i>
                                                    Min Price: 
                                                    @php
                                                        $minPrice = $pricing->items->min('cost');
                                                    @endphp
                                                    @if($minPrice)
                                                        KES {{ number_format($minPrice, 2) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-chart-bar mr-1"></i>
                                                    Max Price: 
                                                    @php
                                                        $maxPrice = $pricing->items->max('cost');
                                                    @endphp
                                                    @if($maxPrice)
                                                        KES {{ number_format($maxPrice, 2) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div>
                    {{ $pricings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Confirm Delete
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this pricing record?</p>
                <p class="font-weight-bold" id="deleteItemName"></p>
                <p class="text-danger small">
                    <i class="fas fa-info-circle mr-1"></i>
                    This action cannot be undone. All associated zone pricing data will be permanently deleted.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt mr-1"></i>Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .pricing-card {
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
    }
    
    .pricing-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .pricing-header {
        transition: background-color 0.2s ease;
    }
    
    .pricing-header:hover {
        background-color: #e9ecef !important;
    }
    
    .collapse-icon {
        transition: transform 0.3s ease;
        font-size: 1rem;
        color: #6c757d;
    }
    
    .pricing-table {
        margin-bottom: 0;
    }
    
    .pricing-table th,
    .pricing-table td {
        padding: 10px 8px;
        vertical-align: middle;
    }
    
    .pricing-table tbody tr:hover {
        background-color: rgba(0, 143, 64, 0.05);
    }
    
    .price-value {
        font-weight: 500;
        color: #008f40;
    }
    
    .has-price {
        background-color: rgba(0, 143, 64, 0.02);
    }
    
    .no-price {
        color: #adb5bd;
    }
    
    .pricing-stats {
        border-top: 1px solid #dee2e6;
    }
    
    .btn-group .btn {
        border-radius: 4px;
    }
    
    /* Animation for collapsed state */
    .collapsed .collapse-icon {
        transform: rotate(0deg);
    }
    
    [aria-expanded="true"] .collapse-icon {
        transform: rotate(90deg);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .pricing-table {
            font-size: 0.85rem;
        }
        
        .pricing-table th,
        .pricing-table td {
            padding: 6px 4px;
        }
        
        .pricing-header h5 {
            font-size: 1rem;
        }
        
        .btn-group .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
    
    /* Zebra striping for better readability */
    .pricing-table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    
    /* Tooltip style for empty cells */
    .no-price:hover {
        cursor: default;
    }
    
    /* Statistics icons */
    .pricing-stats i {
        color: #008f40;
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete(pricingId, pricingName) {
        const modal = $('#deleteModal');
        const deleteForm = $('#deleteForm');
        
        // Update modal content
        // $('#deleteItemName').html(`<strong>${pricingName}</strong> (ID: ${pricingId})`);
        
        // // Set form action
        
        // Show modal
        modal.modal('show');
    }
    
    // Handle collapse icon animation
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all collapse elements
        $('.pricing-header').on('click', function() {
            const target = $(this).data('target');
            const icon = $(this).find('.collapse-icon');
            
            // Toggle icon rotation
            setTimeout(function() {
                if ($(target).hasClass('show')) {
                    icon.css('transform', 'rotate(90deg)');
                } else {
                    icon.css('transform', 'rotate(0deg)');
                }
            }, 100);
        });
        
        // Set initial icon state for expanded cards
        $('.collapse.show').each(function() {
            const parentHeader = $(this).closest('.pricing-card').find('.pricing-header');
            const icon = parentHeader.find('.collapse-icon');
            icon.css('transform', 'rotate(90deg)');
        });
        
        // Add keyboard accessibility
        $('.pricing-header').on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).click();
            }
        }).attr('tabindex', '0');
        
        // Add tooltip for empty cells
        $('.no-price').attr('title', 'No pricing configured for this route');
    });
    
    // Handle delete confirmation with enter key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && $('#deleteModal').hasClass('show')) {
            const activeElement = document.activeElement;
            if (activeElement && activeElement.type === 'submit') {
                activeElement.click();
            }
        }
    });
    
    // Flash message auto-hide after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush
</div>