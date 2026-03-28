<div>
    <div>
        <div class="dashboard-section">
            <!-- Form Header -->
            <div class="section-header">
                <div>
                    <h3 class="section-title">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Point
                    </h3>
                    <p class="section-subtitle">
                        Update pick-up/drop-off point details
                        <span class="badge bg-light text-dark ms-2">ID: {{ $point->code }}</span>
                    </p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-outline-secondary" wire:click="$dispatch('cancelEdit')">
                        <i class="bi bi-arrow-left me-2"></i>
                        Back
                    </button>
                    @if($point->status === 'active')
                    <button class="btn btn-outline-warning" wire:click="$set('status', 'inactive')">
                        <i class="bi bi-pause-circle me-2"></i>
                        Deactivate
                    </button>
                    @else
                    <button class="btn btn-outline-success" wire:click="$set('status', 'active')">
                        <i class="bi bi-play-circle me-2"></i>
                        Activate
                    </button>
                    @endif
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Quick Stats -->
            <div class="quick-stats mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="bi bi-calendar"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">Created</div>
                                <div class="stat-label">{{ $point->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">Last Updated</div>
                                <div class="stat-label">{{ $point->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">Capacity</div>
                                <div class="stat-label">{{ $point->capacity ?? 'N/A' }} parcels</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                @if($point->is_24_hours)
                                <i class="bi bi-clock"></i>
                                @else
                                <i class="bi bi-calendar-range"></i>
                                @endif
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">Operation</div>
                                <div class="stat-label">{{ $point->is_24_hours ? '24/7' : 'Scheduled' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="submit">
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-lg-6">
                        <!-- Basic Information Card -->
                        <div class="form-card">
                            <div class="form-card-header">
                                <h5><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                            </div>
                            <div class="form-card-body">
                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-building me-2"></i>
                                        Pick-Up/Drop-Off Point Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" wire:model="name" placeholder="Enter point name">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        <i class="bi bi-activity me-2"></i>
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <div class="status-badges">
                                        <span class="status-badge @if($status === 'active') active @endif"
                                            wire:click="$set('status', 'active')">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Active
                                        </span>
                                        <span class="status-badge @if($status === 'inactive') active @endif"
                                            wire:click="$set('status', 'inactive')">
                                            <i class="bi bi-pause-circle me-1"></i>
                                            Inactive
                                        </span>
                                        <span class="status-badge @if($status === 'maintenance') active @endif"
                                            wire:click="$set('status', 'maintenance')">
                                            <i class="bi bi-tools me-1"></i>
                                            Maintenance
                                        </span>
                                    </div>
                                    <input type="hidden" wire:model="status">
                                    @error('status')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Location Information Card -->
                        <div class="form-card mt-4">
                            <div class="form-card-header">
                                <h5><i class="bi bi-geo me-2"></i>Location Information</h5>
                            </div>
                            <div class="form-card-body">
                                <!-- Address -->
                                <div class="mb-3">
                                    <label for="address" class="form-label">
                                        <i class="bi bi-house-door me-2"></i>
                                        Address <span class="text-danger">*</span>(E.g. Uhuru Street, Uhuru Building/Hous, opposite Equity Bank, Ground floor/ Rm 6") </label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                        id="address" wire:model="address" rows="2"
                                        placeholder="Enter full address"></textarea>
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- City/Town -->
                                <div class="mb-3">
                                    <label for="town_id" class="form-label">
                                        <i class="bi bi-building me-2"></i>
                                        Town/City <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('town_id') is-invalid @enderror"
                                        id="town_id" wire:model="town_id">
                                        <option value="">Select Town/City</option>
                                        @foreach($towns as $town)
                                        <option value="{{ $town->id }}" @selected($town->id == $town_id)>
                                            {{ $town->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('town_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-6">
                        <!-- Contact Information Card -->
                        <div class="form-card">
                            <div class="form-card-header">
                                <h5><i class="bi bi-person-lines-fill me-2"></i>Contact Information</h5>
                            </div>
                            <div class="form-card-body">
                                <!-- Contact Person -->
                                <div class="mb-3">
                                    <label for="contact_person" class="form-label">
                                        <i class="bi bi-person me-2"></i>
                                        Contact Person <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                                        id="contact_person" wire:model="contact_person"
                                        placeholder="Enter contact person name">
                                    @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Contact Email -->
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">
                                        <i class="bi bi-envelope me-2"></i>
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                                        id="contact_email" wire:model="contact_email"
                                        placeholder="Enter email address">
                                    @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Contact Phone -->
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">
                                        <i class="bi bi-telephone me-2"></i>
                                        Phone Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                                        id="contact_phone" wire:model="contact_phone"
                                        placeholder="Enter phone number">
                                    @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Operating Hours Card -->
                        <div class="form-card mt-4">
                            <div class="form-card-header">
                                <h5><i class="bi bi-clock me-2"></i>Operating Hours</h5>
                            </div>
                            <div class="form-card-body">
                                <!-- 24/7 Toggle -->
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is24Hours"
                                            wire:model="is_24_hours">
                                        <label class="form-check-label" for="is24Hours">
                                            <i class="bi bi-clock-history me-2"></i>
                                            24/7 Operation
                                        </label>
                                    </div>
                                </div>

                                <!-- Opening Hours -->
                                <div class="row @if($is_24_hours) d-none @endif">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="opening_hours" class="form-label">
                                                Opening Time
                                            </label>
                                            <input type="time" class="form-control @error('opening_hours') is-invalid @enderror"
                                                id="opening_hours" wire:model="opening_hours"
                                                @if($is_24_hours) disabled @endif>
                                            @error('opening_hours')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="closing_hours" class="form-label">
                                                Closing Time
                                            </label>
                                            <input type="time" class="form-control @error('closing_hours') is-invalid @enderror"
                                                id="closing_hours" wire:model="closing_hours"
                                                @if($is_24_hours) disabled @endif>
                                            @error('closing_hours')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @error('closing_hours')
                                    @if($error->has('closing_hours.after'))
                                    <div class="text-danger small mt-1">Closing time must be after opening time</div>
                                    @endif
                                    @enderror
                                </div>

                                <!-- Days of Operation -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-calendar-week me-2"></i>
                                        Days of Operation
                                    </label>
                                    <div class="days-grid">
                                        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                        <div class="day-checkbox @if(in_array($day, $operating_days)) active @endif"
                                            wire:click="toggleDay('{{ $day }}')">
                                            <input type="checkbox" class="form-check-input"
                                                wire:model="operating_days" value="{{ $day }}">
                                            <label>{{ $day }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('operating_days')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Card -->
                        <div class="form-card mt-4">
                            <div class="form-card-header">
                                <h5><i class="bi bi-card-text me-2"></i>Additional Information</h5>
                            </div>
                            <div class="form-card-body">
                                <!-- Capacity -->
                                <div class="mb-3">
                                    <label for="capacity" class="form-label">
                                        <i class="bi bi-box-seam me-2"></i>
                                        Storage Capacity
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                            id="capacity" wire:model="capacity"
                                            placeholder="Enter maximum parcels capacity">
                                        <span class="input-group-text">parcels</span>
                                    </div>
                                    @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Notes -->
                                <div class="mb-3">
                                    <label for="notes" class="form-label">
                                        <i class="bi bi-sticky me-2"></i>
                                        Notes
                                    </label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                        id="notes" wire:model="notes" rows="3"
                                        placeholder="Any additional notes or instructions"></textarea>
                                    @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions mt-4 pt-4 border-top">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="button" class="btn btn-outline-danger"
                                wire:click="deletePoint"
                                wire:confirm="Are you sure you want to delete this point? This action cannot be undone.">
                                <i class="bi bi-trash me-2"></i>
                                Delete Point
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-secondary me-2" wire:click="resetForm">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                Reset Changes
                            </button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="submit">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Update Point
                                </span>
                                <span wire:loading wire:target="submit">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Updating...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <style>
            /* Quick Stats */
            .quick-stats .stat-card {
                background: #f8f9fa;
                border-radius: 10px;
                padding: 15px;
                display: flex;
                align-items: center;
                gap: 15px;
                border: 1px solid var(--border-color);
                height: 100%;
            }

            .quick-stats .stat-icon {
                width: 50px;
                height: 50px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
            }

            .quick-stats .stat-info {
                flex: 1;
            }

            .quick-stats .stat-value {
                font-size: 0.9rem;
                font-weight: 600;
                color: var(--text-dark);
                margin-bottom: 3px;
            }

            .quick-stats .stat-label {
                font-size: 0.85rem;
                color: var(--text-light);
            }

            /* Card Styling */
            .form-card {
                background: white;
                border-radius: 12px;
                border: 1px solid var(--border-color);
                margin-bottom: 20px;
            }

            .form-card-header {
                padding: 20px 20px 0 20px;
                border-bottom: 1px solid var(--border-color);
            }

            .form-card-header h5 {
                font-size: 1.1rem;
                font-weight: 600;
                color: var(--text-dark);
                margin: 0;
                padding-bottom: 15px;
            }

            .form-card-body {
                padding: 20px;
            }

            /* Form Elements */
            .form-label {
                font-weight: 600;
                color: var(--text-dark);
                margin-bottom: 8px;
                display: flex;
                align-items: center;
            }

            .form-control,
            .form-select {
                border: 1px solid var(--border-color);
                padding: 10px 15px;
                border-radius: 8px;
                font-size: 0.95rem;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.25rem rgba(0, 143, 64, 0.25);
            }

            .input-group-text {
                background-color: #f8f9fa;
                border-color: var(--border-color);
            }

            /* Type Selection Cards */
            .card-type {
                border: 2px solid #dee2e6;
                border-radius: 10px;
                padding: 15px;
                cursor: pointer;
                transition: all 0.3s ease;
                height: 100%;
            }

            .card-type.active {
                border-color: var(--primary-color);
                background-color: rgba(0, 143, 64, 0.05);
            }

            .card-type:hover:not(.active) {
                border-color: #adb5bd;
                background-color: #f8f9fa;
            }

            .card-type .form-check-input {
                display: none;
            }

            .card-type .form-check-label {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                cursor: pointer;
                margin: 0;
                width: 100%;
            }

            .type-icon {
                width: 50px;
                height: 50px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                margin-bottom: 10px;
            }

            .card-type[wire\\:click*="pickup"] .type-icon {
                background-color: rgba(23, 162, 184, 0.1);
                color: #17a2b8;
            }

            .card-type[wire\\:click*="dropoff"] .type-icon {
                background-color: rgba(255, 193, 7, 0.1);
                color: #ffc107;
            }

            .card-type[wire\\:click*="both"] .type-icon {
                background-color: rgba(0, 143, 64, 0.1);
                color: var(--primary-color);
            }

            .card-type.active[wire\\:click*="pickup"] {
                border-color: #17a2b8;
                background-color: rgba(23, 162, 184, 0.05);
            }

            .card-type.active[wire\\:click*="dropoff"] {
                border-color: #ffc107;
                background-color: rgba(255, 193, 7, 0.05);
            }

            .card-type.active[wire\\:click*="both"] {
                border-color: var(--primary-color);
                background-color: rgba(0, 143, 64, 0.05);
            }

            .type-title {
                font-weight: 600;
                font-size: 0.95rem;
                margin-bottom: 3px;
            }

            .type-text small {
                color: var(--text-light);
                font-size: 0.8rem;
            }

            /* Status Badges */
            .status-badges {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .status-badge {
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 0.9rem;
                font-weight: 500;
                cursor: pointer;
                border: 2px solid transparent;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
            }

            .status-badge[wire\\:click*="active"] {
                background-color: rgba(40, 167, 69, 0.1);
                color: #155724;
                border-color: rgba(40, 167, 69, 0.2);
            }

            .status-badge[wire\\:click*="inactive"] {
                background-color: rgba(108, 117, 125, 0.1);
                color: #495057;
                border-color: rgba(108, 117, 125, 0.2);
            }

            .status-badge[wire\\:click*="maintenance"] {
                background-color: rgba(255, 193, 7, 0.1);
                color: #856404;
                border-color: rgba(255, 193, 7, 0.2);
            }

            .status-badge.active {
                border-color: currentColor;
                font-weight: 600;
            }

            .status-badge:hover:not(.active) {
                opacity: 0.8;
            }

            /* Days Grid */
            .days-grid {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 8px;
            }

            .day-checkbox {
                border: 1px solid var(--border-color);
                border-radius: 8px;
                padding: 8px 5px;
                text-align: center;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .day-checkbox.active {
                background-color: var(--primary-color);
                color: white;
                border-color: var(--primary-color);
            }

            .day-checkbox:hover:not(.active) {
                background-color: #f8f9fa;
            }

            .day-checkbox input {
                display: none;
            }

            .day-checkbox label {
                margin: 0;
                cursor: pointer;
                font-weight: 500;
            }

            /* Form Actions */
            .form-actions {
                background: #f8f9fa;
                padding: 20px;
                border-radius: 12px;
            }

            /* Alert Styling */
            .alert {
                border-radius: 10px;
                border: none;
                margin-bottom: 20px;
            }

            .alert-success {
                background-color: rgba(40, 167, 69, 0.1);
                border-left: 4px solid #28a745;
            }

            .alert-danger {
                background-color: rgba(220, 53, 69, 0.1);
                border-left: 4px solid #dc3545;
            }

            /* Loading State */
            .spinner-border {
                width: 1rem;
                height: 1rem;
            }

            /* Responsive Design */
            @media (max-width: 992px) {
                .days-grid {
                    grid-template-columns: repeat(4, 1fr);
                }

                .quick-stats .row {
                    gap: 15px;
                }

                .quick-stats .col-md-3 {
                    flex: 0 0 calc(50% - 7.5px);
                }
            }

            @media (max-width: 768px) {
                .form-card {
                    margin-bottom: 15px;
                }

                .form-card-header,
                .form-card-body {
                    padding: 15px;
                }

                .card-type {
                    padding: 10px;
                }

                .status-badges {
                    flex-direction: column;
                    align-items: stretch;
                }

                .status-badge {
                    text-align: center;
                    justify-content: center;
                }

                .form-actions .d-flex {
                    flex-direction: column;
                    gap: 10px;
                }

                .form-actions .d-flex>div {
                    width: 100%;
                    display: flex;
                    gap: 10px;
                }

                .form-actions .btn {
                    flex: 1;
                }

                .quick-stats .col-md-3 {
                    flex: 0 0 100%;
                }
            }

            @media (max-width: 576px) {
                .days-grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Focus on name field on load
                const nameField = document.getElementById('name');
                if (nameField) {
                    nameField.focus();
                }

                // Validate time when 24/7 is toggled
                const is24HoursToggle = document.getElementById('is24Hours');
                if (is24HoursToggle) {
                    is24HoursToggle.addEventListener('change', function() {
                        const openingTime = document.getElementById('opening_hours');
                        const closingTime = document.getElementById('closing_hours');

                        if (this.checked) {
                            openingTime.disabled = true;
                            closingTime.disabled = true;
                        } else {
                            openingTime.disabled = false;
                            closingTime.disabled = false;
                            openingTime.focus();
                        }
                    });
                }
            });

            // Livewire listeners
            Livewire.on('pointUpdated', (pointId) => {
                // Refresh parent component or show success message
                console.log('Point updated:', pointId);
            });

            Livewire.on('pointDeleted', (pointId) => {
                // Redirect or refresh parent component
                console.log('Point deleted:', pointId);
                window.location.href = '{{ route("partners.pd.index") }}';
            });
        </script>
    </div>
</div>