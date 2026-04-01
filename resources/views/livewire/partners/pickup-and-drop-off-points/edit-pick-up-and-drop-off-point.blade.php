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

                                <!-- City/Town with Search -->
                                <div class="mb-3">
                                    <label for="town_search" class="form-label">
                                        <i class="bi bi-building me-2"></i>
                                        Town/City <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="town_search" 
                                           class="form-control mb-2" 
                                           placeholder="Search town by name..."
                                           autocomplete="off"
                                           value="{{ $towns->firstWhere('id', $town_id)->name ?? '' }}">
                                    <select class="form-select @error('town_id') is-invalid @enderror" 
                                            id="town_id" 
                                            wire:model="town_id"
                                            style="display: none;">
                                        <option value="">Select Town/City</option>
                                        @foreach($towns as $town)
                                        <option value="{{ $town->id }}">{{ $town->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="town_dropdown_container" style="position: relative;">
                                        <div id="town_dropdown" class="town-dropdown">
                                            <div class="town-dropdown-item" data-value="">Select Town/City</div>
                                            @foreach($towns as $town)
                                            <div class="town-dropdown-item" data-value="{{ $town->id }}">{{ $town->name }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @error('town_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
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
                                    <label for="contact_phone_number" class="form-label">
                                        <i class="bi bi-telephone me-2"></i>
                                        Phone Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('contact_phone_number') is-invalid @enderror"
                                        id="contact_phone_number" wire:model="contact_phone_number"
                                        placeholder="Enter phone number">
                                    @error('contact_phone_number')
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

                                <!-- Daily Operating Hours Table -->
                                <div id="daily-hours-container" @if($is_24_hours) style="display: none;" @endif>
                                    <div class="daily-hours-table">
                                        <div class="table-header">
                                            <div class="day-col">Day</div>
                                            <div class="time-col">Opening Time</div>
                                            <div class="time-col">Closing Time</div>
                                            <div class="status-col">Closed</div>
                                        </div>
                                        
                                        @php
                                            $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                        @endphp
                                        
                                        @foreach ($daysOfWeek as $day)
                                        <div class="table-row">
                                            <div class="day-col">
                                                <strong>{{ $day }}</strong>
                                            </div>
                                            <div class="time-col">
                                                <input type="time" 
                                                    class="form-control form-control-sm @error('operating_hours.' . $day . '.opening') is-invalid @enderror"
                                                    wire:model="operating_hours.{{ $day }}.opening"
                                                    placeholder="--:--"
                                                    @if(isset($operating_hours[$day]['closed']) && $operating_hours[$day]['closed']) disabled @endif>
                                            </div>
                                            <div class="time-col">
                                                <input type="time" 
                                                    class="form-control form-control-sm @error('operating_hours.' . $day . '.closing') is-invalid @enderror"
                                                    wire:model="operating_hours.{{ $day }}.closing"
                                                    placeholder="--:--"
                                                    @if(isset($operating_hours[$day]['closed']) && $operating_hours[$day]['closed']) disabled @endif>
                                            </div>
                                            <div class="status-col">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" 
                                                        id="closed_{{ $day }}" 
                                                        wire:model="operating_hours.{{ $day }}.closed"
                                                        wire:change="toggleDayClosed('{{ $day }}')"
                                                        value="1">
                                                    <label class="form-check-label small" for="closed_{{ $day }}">
                                                        Closed
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @error('operating_hours.' . $day . '.opening')
                                        <div class="text-danger small mt-1 mb-2">{{ $message }}</div>
                                        @enderror
                                        @error('operating_hours.' . $day . '.closing')
                                        <div class="text-danger small mt-1 mb-2">{{ $message }}</div>
                                        @enderror
                                        @endforeach
                                    </div>
                                    
                                    <div class="mt-3 text-muted small">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Uncheck "Closed" and set opening/closing times for operating days.
                                    </div>
                                </div>
                                
                                <!-- 24/7 Message -->
                                <div id="twentyfour-seven-message" @if(!$is_24_hours) style="display: none;" @endif>
                                    <div class="alert alert-info">
                                        <i class="bi bi-infinity me-2"></i>
                                        This point operates 24 hours a day, 7 days a week.
                                    </div>
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

            .form-control-sm {
                padding: 6px 10px;
                font-size: 0.875rem;
            }

            .input-group-text {
                background-color: #f8f9fa;
                border-color: var(--border-color);
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

            /* Daily Operating Hours Table */
            .daily-hours-table {
                border: 1px solid var(--border-color);
                border-radius: 10px;
                overflow: hidden;
            }

            .table-header {
                display: grid;
                grid-template-columns: 2fr 1.5fr 1.5fr 1fr;
                background: #f8f9fa;
                border-bottom: 1px solid var(--border-color);
                font-weight: 600;
                font-size: 0.85rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: var(--text-light);
            }

            .table-row {
                display: grid;
                grid-template-columns: 2fr 1.5fr 1.5fr 1fr;
                border-bottom: 1px solid var(--border-color);
                align-items: center;
            }

            .table-row:last-child {
                border-bottom: none;
            }

            .day-col, .time-col, .status-col {
                padding: 12px 15px;
            }

            .day-col {
                font-weight: 500;
                background-color: #fff;
                border-right: 1px solid var(--border-color);
            }

            .time-col {
                border-right: 1px solid var(--border-color);
            }

            .time-col:last-child {
                border-right: none;
            }

            .status-col {
                text-align: center;
            }

            .status-col .form-check {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin: 0;
                gap: 8px;
            }

            .status-col .form-check-input {
                margin: 0;
                cursor: pointer;
            }

            .status-col .form-check-label {
                cursor: pointer;
                margin: 0;
            }

            /* Town Dropdown Styles */
            .town-dropdown {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                max-height: 250px;
                overflow-y: auto;
                background: white;
                border: 1px solid var(--border-color);
                border-radius: 8px;
                z-index: 1000;
                display: none;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
            .town-dropdown.show {
                display: block;
            }
            .town-dropdown-item {
                padding: 10px 15px;
                cursor: pointer;
                transition: background 0.2s;
                font-size: 0.95rem;
            }
            .town-dropdown-item:hover {
                background-color: #f8f9fa;
            }
            .town-dropdown-item.selected {
                background-color: rgba(0, 143, 64, 0.1);
                font-weight: 600;
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

            .alert-info {
                background-color: rgba(23, 162, 184, 0.1);
                border-left: 4px solid #17a2b8;
                color: #0c5460;
            }

            /* Loading State */
            .spinner-border {
                width: 1rem;
                height: 1rem;
            }

            /* Responsive Design */
            @media (max-width: 992px) {
                .table-header, .table-row {
                    grid-template-columns: 1.5fr 1fr 1fr 0.8fr;
                }
                
                .day-col, .time-col, .status-col {
                    padding: 10px 12px;
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

                .status-badges {
                    flex-direction: column;
                    align-items: stretch;
                }

                .status-badge {
                    text-align: center;
                    justify-content: center;
                }

                .table-header, .table-row {
                    grid-template-columns: 1.2fr 1fr 1fr 0.8fr;
                    font-size: 0.8rem;
                }
                
                .day-col, .time-col, .status-col {
                    padding: 8px 10px;
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
            }

            @media (max-width: 576px) {
                .table-header, .table-row {
                    grid-template-columns: 1fr 0.9fr 0.9fr 0.7fr;
                }
                
                .day-col, .time-col, .status-col {
                    padding: 8px;
                    font-size: 0.75rem;
                }
                
                .time-col input {
                    width: 100%;
                    min-width: 90px;
                }
                
                .status-col .form-check-label {
                    display: none;
                }
                
                .status-col .form-check {
                    justify-content: center;
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

                // ========== SEARCHABLE TOWN DROPDOWN ==========
                const townSearchInput = document.getElementById('town_search');
                const hiddenSelect = document.getElementById('town_id');
                const townDropdown = document.getElementById('town_dropdown');
                const dropdownContainer = document.getElementById('town_dropdown_container');
                
                if (townSearchInput && hiddenSelect && townDropdown) {
                    const dropdownItems = townDropdown.querySelectorAll('.town-dropdown-item');

                    function setTownSelection(value, text) {
                        if (hiddenSelect) {
                            hiddenSelect.value = value;
                            const event = new Event('change', { bubbles: true });
                            hiddenSelect.dispatchEvent(event);
                        }
                        if (townSearchInput) {
                            townSearchInput.value = text === 'Select Town/City' ? '' : text;
                        }
                        dropdownItems.forEach(item => {
                            if (item.getAttribute('data-value') === value) {
                                item.classList.add('selected');
                            } else {
                                item.classList.remove('selected');
                            }
                        });
                        townDropdown.classList.remove('show');
                    }

                    function filterTowns(searchTerm) {
                        const term = searchTerm.toLowerCase().trim();
                        let hasVisible = false;
                        dropdownItems.forEach(item => {
                            const townName = item.textContent.toLowerCase();
                            const isPlaceholder = item.getAttribute('data-value') === '';
                            if (isPlaceholder) {
                                item.style.display = term === '' ? 'block' : 'none';
                            } else {
                                if (term === '' || townName.includes(term)) {
                                    item.style.display = 'block';
                                    hasVisible = true;
                                } else {
                                    item.style.display = 'none';
                                }
                            }
                        });
                        const existingNoResult = townDropdown.querySelector('.no-result');
                        if (existingNoResult) existingNoResult.remove();
                        
                        if (!hasVisible && term !== '') {
                            const noResultItem = document.createElement('div');
                            noResultItem.className = 'town-dropdown-item no-result';
                            noResultItem.textContent = 'No towns found';
                            noResultItem.style.cursor = 'default';
                            noResultItem.style.color = '#6c757d';
                            townDropdown.appendChild(noResultItem);
                        }
                    }

                    townSearchInput.addEventListener('focus', () => {
                        filterTowns(townSearchInput.value);
                        townDropdown.classList.add('show');
                    });

                    townSearchInput.addEventListener('input', (e) => {
                        filterTowns(e.target.value);
                        if (!townDropdown.classList.contains('show')) {
                            townDropdown.classList.add('show');
                        }
                    });

                    document.addEventListener('click', function(event) {
                        if (dropdownContainer && townSearchInput && 
                            !dropdownContainer.contains(event.target) && 
                            event.target !== townSearchInput) {
                            townDropdown.classList.remove('show');
                        }
                    });

                    dropdownItems.forEach(item => {
                        item.addEventListener('click', (e) => {
                            e.stopPropagation();
                            const value = item.getAttribute('data-value');
                            const text = item.textContent;
                            setTownSelection(value, text);
                        });
                    });

                    function syncTownFromHiddenSelect() {
                        const selectedOption = hiddenSelect.options[hiddenSelect.selectedIndex];
                        if (selectedOption && selectedOption.value) {
                            setTownSelection(selectedOption.value, selectedOption.text);
                        }
                    }
                    syncTownFromHiddenSelect();
                    hiddenSelect.addEventListener('change', syncTownFromHiddenSelect);
                }

                // ========== OPERATING HOURS TOGGLE ==========
                const is24HoursCheckbox = document.getElementById('is24Hours');
                const dailyHoursContainer = document.getElementById('daily-hours-container');
                const twentyFourSevenMessage = document.getElementById('twentyfour-seven-message');

                function toggleOperatingDetails() {
                    if (is24HoursCheckbox && dailyHoursContainer && twentyFourSevenMessage) {
                        if (is24HoursCheckbox.checked) {
                            dailyHoursContainer.style.display = 'none';
                            twentyFourSevenMessage.style.display = 'block';
                        } else {
                            dailyHoursContainer.style.display = 'block';
                            twentyFourSevenMessage.style.display = 'none';
                        }
                    }
                }

                toggleOperatingDetails();

                if (is24HoursCheckbox) {
                    is24HoursCheckbox.addEventListener('change', toggleOperatingDetails);
                    
                    if (typeof Livewire !== 'undefined') {
                        Livewire.hook('message.processed', () => {
                            toggleOperatingDetails();
                        });
                    }
                }

                // Enable/disable time inputs when closed checkbox is toggled
                document.querySelectorAll('.table-row input[type="checkbox"]').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const row = this.closest('.table-row');
                        const timeInputs = row.querySelectorAll('input[type="time"]');
                        timeInputs.forEach(input => {
                            input.disabled = this.checked;
                        });
                    });
                });
            });
        </script>
    </div>
</div>