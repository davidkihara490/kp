<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Pick-up & Drop-off Point</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.points.index') }}">Pick-up & Drop-off Points</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Point</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Point Information</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <form wire:submit.prevent="update">
                            <div class="card-body">
                                <!-- Basic Information Section -->
                                <h5 class="mb-3 text-primary">Basic Information</h5>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="partner_id">Partner <span class="text-danger">*</span></label>
                                            <select class="form-control @error('partner_id') is-invalid @enderror"
                                                id="partner_id"
                                                wire:model="partner_id">
                                                <option value="">Select Partner</option>
                                                @foreach($partners as $partner)
                                                <option value="{{ $partner->id }}">{{ $partner->company_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('partner_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Point Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                id="name"
                                                wire:model="name"
                                                placeholder="Enter point name (e.g., Downtown Hub)">
                                            @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="town_id">Town <span class="text-danger">*</span></label>
                                            <select class="form-control @error('town_id') is-invalid @enderror"
                                                id="town_id"
                                                wire:model="town_id">
                                                <option value="">Select Town</option>
                                                @foreach($towns as $town)
                                                <option value="{{ $town->id }}">{{ $town->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('town_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Type <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror"
                                                id="type"
                                                wire:model="type">
                                                <option value="warehouse">Warehouse</option>
                                                <option value="pickup-dropoff">PickUp/DropOff</option>
                                            </select>
                                            @error('type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Details Section -->
                                <h5 class="mt-4 mb-3 text-primary">Location Details</h5>
                                <hr>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text"
                                                class="form-control @error('address') is-invalid @enderror"
                                                id="address"
                                                wire:model="address"
                                                placeholder="Address">
                                            @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">Status <span class="text-danger">*</span></label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status"
                                                wire:model="status">
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                                <option value="pending">Pending</option>
                                                <option value="suspended">Suspended</option>
                                            </select>
                                            @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- Coordinates Section -->
                                <h5 class="mt-4 mb-3 text-primary">GPS Coordinates (Optional)</h5>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="latitude">Latitude</label>
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control @error('latitude') is-invalid @enderror"
                                                    id="latitude"
                                                    wire:model="latitude"
                                                    placeholder="e.g., -1.286389">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-info" id="getLocationBtn">
                                                        <i class="fas fa-map-marker-alt"></i> Get Location
                                                    </button>
                                                </div>
                                            </div>
                                            @error('latitude')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small class="form-text text-muted">Enter decimal degrees (e.g., -1.286389)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="longitude">Longitude</label>
                                            <input type="text"
                                                class="form-control @error('longitude') is-invalid @enderror"
                                                id="longitude"
                                                wire:model="longitude"
                                                placeholder="e.g., 36.817223">
                                            @error('longitude')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small class="form-text text-muted">Enter decimal degrees (e.g., 36.817223)</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information Section -->
                                <h5 class="mt-4 mb-3 text-primary">Contact Information</h5>
                                <hr>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_person">Contact Person</label>
                                            <input type="text"
                                                class="form-control @error('contact_person') is-invalid @enderror"
                                                id="contact_person"
                                                wire:model="contact_person"
                                                placeholder="Name of contact person">
                                            @error('contact_person')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_email">Contact Email</label>
                                            <input type="email"
                                                class="form-control @error('contact_email') is-invalid @enderror"
                                                id="contact_email"
                                                wire:model="contact_email"
                                                placeholder="contact@example.com">
                                            @error('contact_email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_phone_number">Contact Phone Number</label>
                                            <input type="tel"
                                                class="form-control @error('contact_phone_number') is-invalid @enderror"
                                                id="contact_phone_number"
                                                wire:model="contact_phone_number"
                                                placeholder="Enter phone number">
                                            @error('contact_phone_number')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="fas fa-save"></i> Update Point
                                    </span>
                                    <span wire:loading>
                                        <i class="fas fa-spinner fa-spin"></i> Updating...
                                    </span>
                                </button>
                                <a href="{{ route('admin.points.index') }}" class="btn btn-default">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Location Detection -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const getLocationBtn = document.getElementById('getLocationBtn');

        if (getLocationBtn) {
            getLocationBtn.addEventListener('click', function() {
                getCurrentLocation();
            });
        }

        function getCurrentLocation() {
            if (!navigator.geolocation) {
                showMessage('Geolocation is not supported by your browser.', 'warning');
                return;
            }

            // Show loading state
            const btn = getLocationBtn;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Detecting...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Set values to Livewire properties
                    @this.set('latitude', position.coords.latitude.toFixed(6));
                    @this.set('longitude', position.coords.longitude.toFixed(6));

                    // Highlight fields
                    const latInput = document.getElementById('latitude');
                    const lngInput = document.getElementById('longitude');

                    if (latInput && lngInput) {
                        latInput.style.backgroundColor = '#d4edda';
                        lngInput.style.backgroundColor = '#d4edda';
                        setTimeout(() => {
                            latInput.style.backgroundColor = '';
                            lngInput.style.backgroundColor = '';
                        }, 1500);
                    }

                    showMessage('Location detected successfully!', 'success');

                    // Reset button
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                },
                function(error) {
                    let errorMessage = '';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'Location permission denied. Please enable location access.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Location information unavailable.';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'Location request timed out.';
                            break;
                        default:
                            errorMessage = 'An error occurred while detecting location.';
                    }

                    showMessage(errorMessage, 'danger');

                    // Reset button
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        function showMessage(message, type) {
            // Remove existing alerts
            const existingAlert = document.querySelector('.location-alert');
            if (existingAlert) {
                existingAlert.remove();
            }

            // Create alert
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show location-alert`;
            alertDiv.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : (type === 'danger' ? 'fa-exclamation-triangle' : 'fa-info-circle')}"></i>
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            `;

            // Insert at top of card body
            const cardBody = document.querySelector('.card-body');
            if (cardBody) {
                cardBody.insertBefore(alertDiv, cardBody.firstChild);
            }

            // Auto-remove after 5 seconds for success
            if (type === 'success') {
                setTimeout(() => {
                    if (alertDiv) alertDiv.remove();
                }, 5000);
            }
        }
    });
</script>