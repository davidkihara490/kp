<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-truck-loading mr-2"></i>Add New Fleet Vehicle
            </h3>
        </div>
        
        <div class="card-body">
            @include('components.alerts.response-alerts')
            
            <form wire:submit.prevent="submit">
                <!-- Vehicle Information -->
                <div class="card card-primary mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Vehicle Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Registration Number *</label>
                                    <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                           wire:model="registration_number" placeholder="e.g., KAA 123A">
                                    @error('registration_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted">License plate number</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Make *</label>
                                    <input type="text" class="form-control @error('make') is-invalid @enderror" 
                                           wire:model="make" placeholder="e.g., Toyota, Nissan">
                                    @error('make')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Model *</label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                           wire:model="model" placeholder="e.g., Hiace, Dyna">
                                    @error('model')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Vehicle Type *</label>
                                    <select class="form-control @error('vehicle_type') is-invalid @enderror" 
                                            wire:model="vehicle_type">
                                        <option value="van">Van</option>
                                        <option value="truck">Truck</option>
                                        <option value="pickup">Pickup</option>
                                        <option value="motorcycle">Motorcycle</option>
                                        <option value="car">Car</option>
                                    </select>
                                    @error('vehicle_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Year</label>
                                    <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                           wire:model="year" min="1900" max="{{ date('Y') + 1 }}" 
                                           placeholder="e.g., 2020">
                                    @error('year')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Color</label>
                                    <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                           wire:model="color" placeholder="e.g., White, Black">
                                    @error('color')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fuel Type *</label>
                                    <select class="form-control @error('fuel_type') is-invalid @enderror" 
                                            wire:model="fuel_type">
                                        <option value="diesel">Diesel</option>
                                        <option value="petrol">Petrol</option>
                                        <option value="electric">Electric</option>
                                        <option value="hybrid">Hybrid</option>
                                    </select>
                                    @error('fuel_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Load Capacity (kg)</label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                           wire:model="capacity" min="0" step="0.01" placeholder="Maximum load weight">
                                    @error('capacity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted">Maximum payload capacity in kilograms</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ownership & Assignment -->
                <div class="card card-info mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Ownership & Assignment</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Current Driver</label>
                                    <select class="form-control @error('current_driver_id') is-invalid @enderror" 
                                            wire:model="current_driver_id">
                                        <option value="">Select Driver (Optional)</option>
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
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Availability -->
                <div class="card card-warning mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Status & Availability</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            wire:model="status">
                                        <option value="active">Active</option>
                                        <option value="maintenance">Maintenance</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="accident">Accident</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Availability</label>
                                    <div class="custom-control custom-switch mt-2">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_available" wire:model="is_available">
                                        <label class="custom-control-label" for="is_available">
                                            {{ $is_available ? 'Available for Assignment' : 'Not Available' }}
                                        </label>
                                    </div>
                                    @error('is_available')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Dates -->
                <div class="card card-success mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Important Dates</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Registration Expiry</label>
                                    <input type="date" class="form-control @error('registration_expiry') is-invalid @enderror" 
                                           wire:model="registration_expiry">
                                    @error('registration_expiry')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Insurance Expiry</label>
                                    <input type="date" class="form-control @error('insurance_expiry') is-invalid @enderror" 
                                           wire:model="insurance_expiry">
                                    @error('insurance_expiry')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Last Service Date</label>
                                    <input type="date" class="form-control @error('last_service_date') is-invalid @enderror" 
                                           wire:model="last_service_date">
                                    @error('last_service_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Next Service Date</label>
                                    <input type="date" class="form-control @error('next_service_date') is-invalid @enderror" 
                                           wire:model="next_service_date">
                                    @error('next_service_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="card card-secondary mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Additional Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      wire:model="notes" rows="3" 
                                      placeholder="Any additional information about this vehicle..."></textarea>
                            @error('notes')
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
                <span wire:loading.remove wire:target="submit">
                    <i class="fas fa-plus mr-2"></i>Add Fleet Vehicle
                </span>
                <span wire:loading wire:target="submit">
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    Adding...
                </span>
            </button>
            <a href="{{ route('partners.fleet.index') }}" class="btn btn-secondary">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <div class="float-right">
                <span class="text-muted">
                    Fields marked with * are required
                </span>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        margin-bottom: 20px;
    }
    .custom-switch {
        padding-left: 2.25rem;
    }
</style>
@endpush