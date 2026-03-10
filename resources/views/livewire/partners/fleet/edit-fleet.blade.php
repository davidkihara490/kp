<div>
<div>
    <div class="fleet-edit">
        <div class="card shadow-lg border-0">
            <!-- Header -->
            <div class="card-header bg-white border-0 py-4 px-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('partners.fleet.index') }}" 
                           class="btn btn-outline-secondary btn-sm me-3"
                           data-bs-toggle="tooltip" title="Back to Fleet">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h3 class="card-title fw-bold mb-1">
                                <i class="fas fa-edit text-primary me-2"></i>
                                Edit Fleet Vehicle
                            </h3>
                            <p class="text-muted mb-0 small">
                                Update vehicle details and information
                            </p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="vehicle-badge bg-primary bg-opacity-10 px-3 py-2 rounded">
                            <span class="text-primary fw-bold">{{ $fleet->registration_number }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body px-5 pt-4">
                @include('components.alerts.response-alerts')
                
                <!-- Quick Status Update -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-medium me-3">Quick Status Update:</span>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" 
                                                    class="btn btn-outline-success {{ $status === 'active' ? 'active' : '' }}"
                                                    wire:click="updateStatus('active')">
                                                <i class="fas fa-check-circle me-1"></i> Active
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-outline-warning {{ $status === 'maintenance' ? 'active' : '' }}"
                                                    wire:click="updateStatus('maintenance')">
                                                <i class="fas fa-tools me-1"></i> Maintenance
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-outline-secondary {{ $status === 'inactive' ? 'active' : '' }}"
                                                    wire:click="updateStatus('inactive')">
                                                <i class="fas fa-pause-circle me-1"></i> Inactive
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-outline-danger {{ $status === 'accident' ? 'active' : '' }}"
                                                    wire:click="updateStatus('accident')">
                                                <i class="fas fa-car-crash me-1"></i> Accident
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="availabilitySwitch" wire:model="is_available">
                                        <label class="form-check-label fw-medium" for="availabilitySwitch">
                                            {{ $is_available ? 'Available' : 'Unavailable' }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="submit">
                    <div class="row">
                        <!-- Left Column - Vehicle Details -->
                        <div class="col-lg-8">
                            <!-- Vehicle Information Card -->
                            <div class="card border mb-4">
                                <div class="card-header bg-light py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-car text-primary"></i>
                                        </div>
                                        <h5 class="mb-0 fw-bold">Vehicle Information</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Registration Number -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">
                                                Registration Number <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-id-card text-muted"></i>
                                                </span>
                                                <input type="text" 
                                                       class="form-control @error('registration_number') is-invalid @enderror"
                                                       wire:model="registration_number"
                                                       placeholder="Enter registration number">
                                            </div>
                                            @error('registration_number')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Vehicle's license plate number</small>
                                        </div>

                                        <!-- Make & Model -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Make <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('make') is-invalid @enderror"
                                                   wire:model="make"
                                                   placeholder="e.g., Toyota, Ford">
                                            @error('make')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Model <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('model') is-invalid @enderror"
                                                   wire:model="model"
                                                   placeholder="e.g., Hiace, F-150">
                                            @error('model')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Vehicle Type & Year -->
                                        <div class="col-md-3">
                                            <label class="form-label fw-medium">Vehicle Type <span class="text-danger">*</span></label>
                                            <select class="form-select @error('vehicle_type') is-invalid @enderror"
                                                    wire:model="vehicle_type">
                                                <option value="van">Van</option>
                                                <option value="truck">Truck</option>
                                                <option value="pickup">Pickup</option>
                                                <option value="motorcycle">Motorcycle</option>
                                                <option value="car">Car</option>
                                            </select>
                                            @error('vehicle_type')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label fw-medium">Year</label>
                                            <input type="number" 
                                                   class="form-control @error('year') is-invalid @enderror"
                                                   wire:model="year"
                                                   min="1900" 
                                                   max="{{ date('Y') + 1 }}"
                                                   placeholder="e.g., 2022">
                                            @error('year')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Color & Fuel Type -->
                                        <div class="col-md-3">
                                            <label class="form-label fw-medium">Color</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-palette text-muted"></i>
                                                </span>
                                                <input type="text" 
                                                       class="form-control @error('color') is-invalid @enderror"
                                                       wire:model="color"
                                                       placeholder="e.g., White, Black">
                                            </div>
                                            @error('color')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label fw-medium">Fuel Type <span class="text-danger">*</span></label>
                                            <select class="form-select @error('fuel_type') is-invalid @enderror"
                                                    wire:model="fuel_type">
                                                <option value="diesel">Diesel</option>
                                                <option value="petrol">Petrol</option>
                                                <option value="electric">Electric</option>
                                                <option value="hybrid">Hybrid</option>
                                            </select>
                                            @error('fuel_type')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Capacity -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium">Load Capacity (kg)</label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       class="form-control @error('capacity') is-invalid @enderror"
                                                       wire:model="capacity"
                                                       min="0" 
                                                       step="0.01"
                                                       placeholder="Maximum load weight">
                                                <span class="input-group-text">kg</span>
                                            </div>
                                            @error('capacity')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Maximum payload capacity in kilograms</small>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Driver Assignment -->
                            <div class="card border mb-4">
                                <div class="card-header bg-light py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-user text-info"></i>
                                        </div>
                                        <h5 class="mb-0 fw-bold">Driver Assignment</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label class="form-label fw-medium">Assign Driver</label>
                                            <select class="form-select @error('current_driver_id') is-invalid @enderror"
                                                    wire:model="current_driver_id">
                                                <option value="">No Driver Assigned</option>
                                                @foreach($drivers as $driver)
                                                    <option value="{{ $driver->id }}">
                                                        {{ $driver->first_name }} {{ $driver->last_name }}
                                                        @if($driver->driver_id)
                                                            ({{ $driver->driver_id }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('current_driver_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-medium">Status</label>
                                            <select class="form-select @error('status') is-invalid @enderror"
                                                    wire:model="status">
                                                <option value="active">Active</option>
                                                <option value="maintenance">Maintenance</option>
                                                <option value="inactive">Inactive</option>
                                                <option value="accident">Accident</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Important Dates & Notes -->
                        <div class="col-lg-4">
                            <!-- Important Dates -->
                            <div class="card border mb-4">
                                <div class="card-header bg-light py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-calendar-alt text-success"></i>
                                        </div>
                                        <h5 class="mb-0 fw-bold">Important Dates</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="date-grid">
                                        <!-- Registration Expiry -->
                                        <div class="date-item mb-3">
                                            <label class="form-label fw-medium small">Registration Expiry</label>
                                            <div class="input-group">
                                                <input type="date" 
                                                       class="form-control @error('registration_expiry') is-invalid @enderror"
                                                       wire:model="registration_expiry">
                                            </div>
                                            @error('registration_expiry')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            @if($registration_expiry)
                                                @php
                                                    $daysUntilExpiry = \Carbon\Carbon::parse($registration_expiry)->diffInDays(now(), false);
                                                @endphp
                                                <div class="mt-1">
                                                    @if($daysUntilExpiry > 0)
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            Expired {{ abs($daysUntilExpiry) }} days ago
                                                        </span>
                                                    @elseif($daysUntilExpiry >= -30)
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Expires in {{ abs($daysUntilExpiry) }} days
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            Valid
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Insurance Expiry -->
                                        <div class="date-item mb-3">
                                            <label class="form-label fw-medium small">Insurance Expiry</label>
                                            <div class="input-group">
                                                <input type="date" 
                                                       class="form-control @error('insurance_expiry') is-invalid @enderror"
                                                       wire:model="insurance_expiry">
                                            </div>
                                            @error('insurance_expiry')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Service Dates -->
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="date-item">
                                                    <label class="form-label fw-medium small">Last Service</label>
                                                    <input type="date" 
                                                           class="form-control @error('last_service_date') is-invalid @enderror"
                                                           wire:model="last_service_date">
                                                    @error('last_service_date')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="date-item">
                                                    <label class="form-label fw-medium small">Next Service</label>
                                                    <input type="date" 
                                                           class="form-control @error('next_service_date') is-invalid @enderror"
                                                           wire:model="next_service_date">
                                                    @error('next_service_date')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="card border mb-4">
                                <div class="card-header bg-light py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper bg-secondary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-sticky-note text-secondary"></i>
                                        </div>
                                        <h5 class="mb-0 fw-bold">Additional Notes</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-floating">
                                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                                  wire:model="notes"
                                                  placeholder="Add notes about this vehicle..."
                                                  style="height: 150px"></textarea>
                                        <label>Vehicle notes, maintenance history, special instructions...</label>
                                    </div>
                                    @error('notes')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Vehicle Summary -->
                            <div class="card border">
                                <div class="card-header bg-light py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-info-circle text-primary"></i>
                                        </div>
                                        <h5 class="mb-0 fw-bold">Vehicle Summary</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="vehicle-summary">
                                        <div class="summary-item d-flex justify-content-between mb-2">
                                            <span class="text-muted">Registration:</span>
                                            <span class="fw-medium">{{ $fleet->registration_number }}</span>
                                        </div>
                                        <div class="summary-item d-flex justify-content-between mb-2">
                                            <span class="text-muted">Vehicle Type:</span>
                                            <span class="badge bg-light text-dark">{{ ucfirst($vehicle_type) }}</span>
                                        </div>
                                        <div class="summary-item d-flex justify-content-between mb-2">
                                            <span class="text-muted">Fuel Type:</span>
                                            <span class="badge bg-light text-dark">{{ ucfirst($fuel_type) }}</span>
                                        </div>
                                        <div class="summary-item d-flex justify-content-between mb-2">
                                            <span class="text-muted">Status:</span>
                                            <span class="badge badge-status-{{ $status }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </div>
                                        <div class="summary-item d-flex justify-content-between">
                                            <span class="text-muted">Availability:</span>
                                            <span class="badge {{ $is_available ? 'bg-success' : 'bg-danger' }}">
                                                {{ $is_available ? 'Available' : 'Unavailable' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="sticky-footer mt-4 pt-4 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('partners.fleet.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="button" class="btn btn-outline-primary"
                                        onclick="location.reload()">
                                    <i class="fas fa-redo me-2"></i>Reset
                                </button>
                                <button type="submit" 
                                        class="btn btn-primary btn-gradient"
                                        wire:loading.attr="disabled"
                                        wire:target="submit">
                                    <span wire:loading.remove wire:target="submit">
                                        <i class="fas fa-save me-2"></i>Update Vehicle
                                    </span>
                                    <span wire:loading wire:target="submit">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Updating...
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Fields marked with <span class="text-danger">*</span> are required
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .fleet-edit {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 10px 24px;
        font-weight: 500;
    }
    
    .btn-gradient:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4199 100%);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        transition: all 0.3s ease;
    }
    
    .icon-wrapper {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .vehicle-badge {
        border: 2px solid rgba(102, 126, 234, 0.2);
    }
    
    .badge-status-active {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.2);
    }
    
    .badge-status-maintenance {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }
    
    .badge-status-inactive {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.2);
    }
    
    .badge-status-accident {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }
    
    .date-grid {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .date-item label {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    
    .vehicle-summary {
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        padding: 16px;
        border-radius: 8px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .summary-item {
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .summary-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .sticky-footer {
        background: white;
        position: sticky;
        bottom: 0;
        z-index: 100;
        padding-bottom: 20px;
        margin-bottom: -20px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .btn-group .btn.active {
        background-color: rgba(102, 126, 234, 0.1);
        border-color: #667eea;
        color: #667eea;
        font-weight: 500;
    }
    
    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .form-check-input:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Auto-format registration number to uppercase
        const regInput = document.querySelector('[wire\\:model="registration_number"]');
        if (regInput) {
            regInput.addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
            });
        }
        
        // Calculate next service date if last service date changes
        const lastServiceInput = document.querySelector('[wire\\:model="last_service_date"]');
        if (lastServiceInput) {
            lastServiceInput.addEventListener('change', function(e) {
                if (this.value) {
                    const lastServiceDate = new Date(this.value);
                    const nextServiceDate = new Date(lastServiceDate);
                    nextServiceDate.setMonth(nextServiceDate.getMonth() + 3);
                    
                    // Format date to YYYY-MM-DD
                    const formattedDate = nextServiceDate.toISOString().split('T')[0];
                    
                    // Update the next service date field
                    const nextServiceInput = document.querySelector('[wire\\:model="next_service_date"]');
                    if (nextServiceInput) {
                        nextServiceInput.value = formattedDate;
                        // Dispatch change event to update Livewire model
                        nextServiceInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
            });
        }
    });
</script>
@endpush</div>
