<div>
    <div>
        <div class="dashboard-section">
            <!-- Form Header -->
            <div class="section-header">
                <div>
                    <h3 class="section-title">Create New Point</h3>
                    <p class="section-subtitle">Add a new pick-up or drop-off point</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-outline-secondary" wire:click="$dispatch('cancelCreate')">
                        <i class="bi bi-arrow-left me-2"></i>
                        Back
                    </button>
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
                                        <span class="status-badge @if ($status === 'active') active @endif"
                                            wire:click="$set('status', 'active')">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Active
                                        </span>
                                        <span class="status-badge @if ($status === 'inactive') active @endif"
                                            wire:click="$set('status', 'inactive')">
                                            <i class="bi bi-pause-circle me-1"></i>
                                            Inactive
                                        </span>
                                        <span class="status-badge @if ($status === 'maintenance') active @endif"
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
                                        Address <span class="text-danger">*</span>(E.g. Uhuru Street, Uhuru Building/Hous, opposite Equity Bank, Ground floor/ Rm 6")
                                    </label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" wire:model="address"
                                        rows="2" placeholder="Enter full address"></textarea>
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- City/Town with Searchable Dropdown (No component edit) -->
                                <div class="mb-3">
                                    <label for="town_search" class="form-label">
                                        <i class="bi bi-building me-2"></i>
                                        Town/City <span class="text-danger">*</span>
                                    </label>
                                    <!-- Search input for filtering towns -->
                                    <input type="text" 
                                           id="town_search" 
                                           class="form-control mb-2" 
                                           placeholder="Search town by name..."
                                           autocomplete="off">
                                    <!-- Hidden select to maintain wire:model binding -->
                                    <select class="form-select @error('town_id') is-invalid @enderror" 
                                            id="town_id" 
                                            wire:model="town_id"
                                            style="display: none;">
                                        <option value="">Select Town/City</option>
                                        @foreach ($towns as $town)
                                        <option value="{{ $town->id }}">{{ $town->name }}</option>
                                        @endforeach
                                    </select>
                                    <!-- Visible dropdown for searchable functionality -->
                                    <div id="town_dropdown_container" style="position: relative;">
                                        <div id="town_dropdown" class="town-dropdown">
                                            <div class="town-dropdown-item" data-value="">Select Town/City</div>
                                            @foreach ($towns as $town)
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
                                    <input type="text"
                                        class="form-control @error('contact_person') is-invalid @enderror"
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
                                    <input type="email"
                                        class="form-control @error('contact_email') is-invalid @enderror"
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
                                    <input type="text"
                                        class="form-control @error('contact_phone_number') is-invalid @enderror"
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

                                <!-- Daily Operating Hours Table - Shown when 24/7 is NOT selected -->
                                <div id="daily-hours-container" @if ($is_24_hours) style="display: none;" @endif>
                                    <div class="daily-hours-table">
                                        <div class="table-header">
                                            <div class="day-col">Day</div>
                                            <div class="time-col">Opening Time</div>
                                            <div class="time-col">Closing Time</div>
                                            <div class="status-col">Status</div>
                                        </div>
                                        
                                        @php
                                            $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                            $dayAbbr = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                                        @endphp
                                        
                                        @foreach ($daysOfWeek as $index => $day)
                                        <div class="table-row">
                                            <div class="day-col">
                                                <strong>{{ $day }}</strong>
                                                <span class="day-abbr">({{ $dayAbbr[$index] }})</span>
                                            </div>
                                            <div class="time-col">
                                                <input type="time" 
                                                    class="form-control form-control-sm @error('operating_hours.' . $day . '.opening') is-invalid @enderror"
                                                    wire:model="operating_hours.{{ $day }}.opening"
                                                    placeholder="--:--">
                                            </div>
                                            <div class="time-col">
                                                <input type="time" 
                                                    class="form-control form-control-sm @error('operating_hours.' . $day . '.closing') is-invalid @enderror"
                                                    wire:model="operating_hours.{{ $day }}.closing"
                                                    placeholder="--:--">
                                            </div>
                                            <div class="status-col">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" 
                                                        id="closed_{{ $day }}" 
                                                        wire:model="operating_hours.{{ $day }}.closed"
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
                                        Leave time fields empty for days that are closed, or use the "Closed" toggle.
                                    </div>
                                </div>
                                
                                <!-- Simplified view when 24/7 is selected -->
                                <div id="twentyfour-seven-message" @if (!$is_24_hours) style="display: none;" @endif>
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
                                        <input type="number"
                                            class="form-control @error('capacity') is-invalid @enderror"
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
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" wire:model="notes"
                                        rows="3" placeholder="Any additional notes or instructions"></textarea>
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
                        <button type="button" class="btn btn-outline-secondary"
                            wire:click="$dispatch('cancelCreate')">
                            <i class="bi bi-x-circle me-2"></i>
                            Cancel
                        </button>
                        <div>
                            <button type="button" class="btn btn-outline-danger me-2" wire:click="resetForm">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                Reset
                            </button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="submit">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Create Point
                                </span>
                                <span wire:loading wire:target="submit">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Creating...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <style>
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

            .day-abbr {
                font-size: 0.75rem;
                color: #6c757d;
                font-weight: normal;
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
                
                .day-abbr {
                    display: none;
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
                // Auto-generate code if not provided
                if (typeof Livewire !== 'undefined') {
                    Livewire.on('codeGenerated', (code) => {
                        console.log('Generated code:', code);
                    });
                }

                // Focus on name field on load
                const nameField = document.getElementById('name');
                if (nameField) {
                    nameField.focus();
                }

                // ========== SEARCHABLE TOWN DROPDOWN (No component edit) ==========
                const townSearchInput = document.getElementById('town_search');
                const hiddenSelect = document.getElementById('town_id');
                const townDropdown = document.getElementById('town_dropdown');
                const dropdownContainer = document.getElementById('town_dropdown_container');
                
                if (townSearchInput && hiddenSelect && townDropdown) {
                    const dropdownItems = townDropdown.querySelectorAll('.town-dropdown-item');

                    // Function to update the hidden select and dropdown UI based on selected value
                    function setTownSelection(value, text) {
                        // Update hidden select
                        if (hiddenSelect) {
                            hiddenSelect.value = value;
                            // Trigger change event for Livewire to detect the update
                            const event = new Event('change', { bubbles: true });
                            hiddenSelect.dispatchEvent(event);
                        }
                        // Update search input display text
                        if (townSearchInput) {
                            townSearchInput.value = text === 'Select Town/City' ? '' : text;
                        }
                        // Update selected class in dropdown
                        dropdownItems.forEach(item => {
                            if (item.getAttribute('data-value') === value) {
                                item.classList.add('selected');
                            } else {
                                item.classList.remove('selected');
                            }
                        });
                        // Hide dropdown after selection
                        townDropdown.classList.remove('show');
                    }

                    // Filter dropdown items based on search input
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
                        // Remove any existing no-result message
                        const existingNoResult = townDropdown.querySelector('.no-result');
                        if (existingNoResult) existingNoResult.remove();
                        
                        // If no visible results and not empty search, show a "no results" message
                        if (!hasVisible && term !== '') {
                            const noResultItem = document.createElement('div');
                            noResultItem.className = 'town-dropdown-item no-result';
                            noResultItem.textContent = 'No towns found';
                            noResultItem.style.cursor = 'default';
                            noResultItem.style.color = '#6c757d';
                            noResultItem.addEventListener('click', (e) => e.stopPropagation());
                            townDropdown.appendChild(noResultItem);
                        }
                    }

                    // Show dropdown when search input is focused or clicked
                    townSearchInput.addEventListener('focus', () => {
                        filterTowns(townSearchInput.value);
                        townDropdown.classList.add('show');
                    });

                    townSearchInput.addEventListener('click', () => {
                        filterTowns(townSearchInput.value);
                        townDropdown.classList.add('show');
                    });

                    // Filter as user types
                    townSearchInput.addEventListener('input', (e) => {
                        filterTowns(e.target.value);
                        if (!townDropdown.classList.contains('show')) {
                            townDropdown.classList.add('show');
                        }
                    });

                    // Close dropdown when clicking outside
                    document.addEventListener('click', function(event) {
                        if (dropdownContainer && townSearchInput && 
                            !dropdownContainer.contains(event.target) && 
                            event.target !== townSearchInput) {
                            townDropdown.classList.remove('show');
                        }
                    });

                    // Handle click on dropdown items
                    dropdownItems.forEach(item => {
                        item.addEventListener('click', (e) => {
                            e.stopPropagation();
                            const value = item.getAttribute('data-value');
                            const text = item.textContent;
                            setTownSelection(value, text);
                        });
                    });

                    // Initialize selected value from Livewire model (via hidden select)
                    function syncTownFromHiddenSelect() {
                        const selectedOption = hiddenSelect.options[hiddenSelect.selectedIndex];
                        if (selectedOption && selectedOption.value) {
                            setTownSelection(selectedOption.value, selectedOption.text);
                        } else {
                            setTownSelection('', '');
                        }
                    }
                    syncTownFromHiddenSelect();
                    // Listen for changes on hidden select (in case Livewire updates it)
                    hiddenSelect.addEventListener('change', syncTownFromHiddenSelect);
                }

                // ========== OPERATING HOURS: Hide daily hours when 24/7 is selected ==========
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

                // Initial toggle on page load
                toggleOperatingDetails();

                // Listen for changes on the checkbox
                if (is24HoursCheckbox) {
                    is24HoursCheckbox.addEventListener('change', toggleOperatingDetails);
                    
                    // Also listen for Livewire updates (in case model changes programmatically)
                    if (typeof Livewire !== 'undefined') {
                        Livewire.hook('message.processed', () => {
                            toggleOperatingDetails();
                        });
                    }
                }

                // Helper function to validate closing time is after opening time
                function validateTimePairs() {
                    const rows = document.querySelectorAll('.table-row');
                    rows.forEach(row => {
                        const openingInput = row.querySelector('input[type="time"]:first-child');
                        const closingInput = row.querySelector('input[type="time"]:last-child');
                        const closedCheckbox = row.querySelector('input[type="checkbox"]');
                        
                        if (openingInput && closingInput && closedCheckbox) {
                            const opening = openingInput.value;
                            const closing = closingInput.value;
                            const isClosed = closedCheckbox.checked;
                            
                            if (!isClosed && opening && closing && opening >= closing) {
                                closingInput.setCustomValidity('Closing time must be after opening time');
                            } else {
                                closingInput.setCustomValidity('');
                            }
                        }
                    });
                }
                
                // Add validation listeners
                document.querySelectorAll('.table-row input[type="time"]').forEach(input => {
                    input.addEventListener('change', validateTimePairs);
                    input.addEventListener('input', validateTimePairs);
                });
                
                document.querySelectorAll('.table-row input[type="checkbox"]').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const row = this.closest('.table-row');
                        const timeInputs = row.querySelectorAll('input[type="time"]');
                        timeInputs.forEach(input => {
                            if (this.checked) {
                                input.disabled = true;
                            } else {
                                input.disabled = false;
                            }
                        });
                        validateTimePairs();
                    });
                });
                
                // Initial validation
                validateTimePairs();
            });
        </script>
    </div>
</div>