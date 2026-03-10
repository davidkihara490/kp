<div>
<div>
    <div class="dashboard-section">
        <!-- Header Section -->
        <div class="section-header">
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="driver-avatar">
                        @if($driver->user && $driver->user->avatar)
                            <img src="{{ $driver->user->avatar }}" 
                                 alt="{{ $driver->full_name }}" 
                                 class="rounded-circle profile-photo-lg">
                        @else
                            <div class="avatar-placeholder-lg bg-primary rounded-circle">
                                {{ substr($driver->first_name, 0, 1) }}{{ substr($driver->last_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="section-title mb-1">
                            {{ $driver->full_name }}
                            <small class="text-muted fs-6">({{ $driver->employee_number ?? 'No ID' }})</small>
                        </h3>
                        <p class="section-subtitle mb-2">
                            <i class="bi bi-geo-alt me-1"></i>{{ $driver->city ?? 'N/A' }} • 
                            <i class="bi bi-telephone me-1 ms-2"></i>{{ $driver->phone_number }} • 
                            <i class="bi bi-envelope me-1 ms-2"></i>{{ $driver->email }}
                        </p>
                        <div class="d-flex gap-2 align-items-center">
                            @php
                                $statusBadge = $driver->getStatusBadge();
                            @endphp
                            <span class="status-badge status-{{ $statusBadge['color'] }}">
                                <i class="bi {{ str_replace('fa', 'bi', $statusBadge['icon']) }} me-1"></i>
                                {{ ucfirst($driver->status) }}
                            </span>
                            <span class="badge bg-{{ $driver->is_available ? 'success' : 'secondary' }}">
                                <i class="bi bi-{{ $driver->is_available ? 'check' : 'x' }}-circle me-1"></i>
                                {{ $driver->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                            @php
                                $licenseValidity = $this->calculateLicenseValidity();
                            @endphp
                            <span class="badge bg-{{ $licenseValidity['color'] }}">
                                <i class="bi bi-{{ $licenseValidity['icon'] }} me-1"></i>
                                {{ $licenseValidity['text'] }}
                            </span>
                            @if($driver->rating)
                                <span class="badge bg-warning">
                                    <i class="bi bi-star-fill me-1"></i>
                                    {{ number_format($driver->rating, 1) }}/5.0
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('partners.drivers.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Drivers
                </a>
                <a href="{{ route('partners.drivers.edit', $driverId) }}" class="btn btn-primary me-2">
                    <i class="bi bi-pencil me-2"></i>
                    Edit
                </a>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear me-2"></i>
                        Actions
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @if($driver->is_available)
                            <li>
                                <button class="dropdown-item" wire:click="markAsUnavailable">
                                    <i class="bi bi-person-dash text-warning me-2"></i>
                                    Mark Unavailable
                                </button>
                            </li>
                        @else
                            <li>
                                <button class="dropdown-item" wire:click="markAsAvailable">
                                    <i class="bi bi-person-check text-success me-2"></i>
                                    Mark as Available
                                </button>
                            </li>
                        @endif
                        @if($driver->status !== 'active')
                            <li>
                                <button class="dropdown-item" wire:click="updateStatus('active')">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Activate Driver
                                </button>
                            </li>
                        @endif
                        @if($driver->status !== 'suspended')
                            <li>
                                <button class="dropdown-item" wire:click="updateStatus('suspended')">
                                    <i class="bi bi-ban text-danger me-2"></i>
                                    Suspend Driver
                                </button>
                            </li>
                        @endif
                        @if($driver->status !== 'on_leave')
                            <li>
                                <button class="dropdown-item" wire:click="updateStatus('on_leave')">
                                    <i class="bi bi-umbrella text-warning me-2"></i>
                                    Mark as On Leave
                                </button>
                            </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button class="dropdown-item" wire:click="exportDocuments">
                                <i class="bi bi-download text-secondary me-2"></i>
                                Export Documents
                            </button>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            {{-- <a class="dropdown-item text-danger" 
                               onclick="confirmDelete('{{ $driver->full_name }}', '{{ route('partners.drivers.delete', $driverId) }}')">
                                <i class="bi bi-trash me-2"></i>
                                Delete Driver
                            </a> --}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <!-- <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $performanceStats['total_deliveries'] }}</div>
                        <div class="stat-label">Total Deliveries</div>
                        <div class="stat-subtext">{{ $performanceStats['success_rate'] }}% success rate</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon distance">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $performanceStats['total_distance'] }}</div>
                        <div class="stat-label">Total Distance</div>
                        <div class="stat-subtext">{{ $performanceStats['average_delivery_time'] }} avg time</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon rating">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ number_format($performanceStats['rating'], 1) }}</div>
                        <div class="stat-label">Average Rating</div>
                        <div class="stat-subtext">{{ $performanceStats['on_time_rate'] }}% on-time rate</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon experience">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $this->getAge() }}</div>
                        <div class="stat-label">Age / Experience</div>
                        <div class="stat-subtext">{{ $this->getEmploymentDuration() }} with us</div>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Tabs Navigation -->
        <div class="tabs-navigation mb-4">
            <ul class="nav nav-tabs" id="driverTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'overview' ? 'active' : '' }}" 
                            wire:click="changeTab('overview')">
                        <i class="bi bi-info-circle me-2"></i>
                        Overview
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'trips' ? 'active' : '' }}" 
                            wire:click="changeTab('trips')">
                        <i class="bi bi-map me-2"></i>
                        Trips
                        <span class="badge bg-secondary ms-2">0</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'deliveries' ? 'active' : '' }}" 
                            wire:click="changeTab('deliveries')">
                        <i class="bi bi-box-seam me-2"></i>
                        Deliveries
                        <span class="badge bg-secondary ms-2">{{ $performanceStats['total_deliveries'] }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'documents' ? 'active' : '' }}" 
                            wire:click="changeTab('documents')">
                        <i class="bi bi-files me-2"></i>
                        Documents
                        <span class="badge bg-secondary ms-2">{{ count($documents) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $activeTab === 'performance' ? 'active' : '' }}" 
                            wire:click="changeTab('performance')">
                        <i class="bi bi-bar-chart me-2"></i>
                        Performance
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Overview Tab -->
            @if($activeTab === 'overview')
                <div class="row">
                    <!-- Left Column - Driver Details -->
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Driver Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Full Name:</th>
                                                <td><strong>{{ $driver->full_name }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Date of Birth:</th>
                                                <td>{{ $driver->date_of_birth?->format('M d, Y') ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Age:</th>
                                                <td>{{ $this->getAge() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Gender:</th>
                                                <td>{{ ucfirst($driver->gender) }}</td>
                                            </tr>
                                            <tr>
                                                <th>ID Number:</th>
                                                <td>{{ $driver->id_number }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nationality:</th>
                                                <td>{{ $driver->nationality }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Employee Number:</th>
                                                <td>{{ $driver->employee_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Employment Date:</th>
                                                <td>{{ $driver->employment_date?->format('M d, Y') ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Employment Type:</th>
                                                <td>{{ ucfirst($driver->employment_type) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Salary:</th>
                                                <td>
                                                    @if($driver->salary)
                                                        KSh {{ number_format($driver->salary, 2) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Shift Type:</th>
                                                <td>{{ ucfirst($driver->shift_type) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Shift Hours:</th>
                                                <td>{{ $driver->shift_start_time?->format('h:i A') }} - {{ $driver->shift_end_time?->format('h:i A') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="mt-4">
                                    <h6 class="border-bottom pb-2">Contact Information</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th width="40%">Phone:</th>
                                                    <td>
                                                        <a href="tel:{{ $driver->phone_number }}" class="text-decoration-none">
                                                            {{ $driver->phone_number }}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Alternate Phone:</th>
                                                    <td>
                                                        @if($driver->alternate_phone_number)
                                                            <a href="tel:{{ $driver->alternate_phone_number }}" class="text-decoration-none">
                                                                {{ $driver->alternate_phone_number }}
                                                            </a>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Email:</th>
                                                    <td>
                                                        <a href="mailto:{{ $driver->email }}" class="text-decoration-none">
                                                            {{ $driver->email }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th width="40%">Address:</th>
                                                    <td>{{ $driver->address ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>City:</th>
                                                    <td>{{ $driver->city ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>County:</th>
                                                    <td>{{ $driver->county->name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Postal Code:</th>
                                                    <td>{{ $driver->postal_code ?? 'N/A' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transport Partner & Fleet -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="bi bi-building me-2"></i>
                                            Transport Partner
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if($driver->transportPartner)
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar bg-primary me-3">
                                                    <i class="bi bi-building"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $driver->transportPartner->name }}</h6>
                                                    <small class="text-muted">{{ $driver->transportPartner->contact_email ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <th>Contact:</th>
                                                    <td>{{ $driver->transportPartner->contact_phone ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Address:</th>
                                                    <td>{{ $driver->transportPartner->address ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status:</th>
                                                    <td>
                                                        <span class="badge bg-{{ $driver->transportPartner->status === 'active' ? 'success' : 'secondary' }}">
                                                            {{ ucfirst($driver->transportPartner->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        @else
                                            <div class="text-center py-4 text-muted">
                                                <i class="bi bi-building display-6"></i>
                                                <p class="mt-2">No transport partner assigned</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="bi bi-truck me-2"></i>
                                            Assigned Fleet
                                        </h5>
                                        @if($driver->fleet)
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                        type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-gear"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button class="dropdown-item" wire:click="unassignFleet">
                                                            <i class="bi bi-truck text-danger me-2"></i>
                                                            Unassign Fleet
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        @if($driver->fleet)
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar bg-success me-3">
                                                    <i class="bi bi-truck"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $driver->fleet->registration_number }}</h6>
                                                    <small class="text-muted">{{ $driver->fleet->make }} {{ $driver->fleet->model }}</small>
                                                </div>
                                            </div>
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <th>Type:</th>
                                                    <td>{{ ucfirst($driver->fleet->fleet_type) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Year:</th>
                                                    <td>{{ $driver->fleet->year }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Capacity:</th>
                                                    <td>{{ number_format($driver->fleet->capacity, 0) }} kg</td>
                                                </tr>
                                                <tr>
                                                    <th>Status:</th>
                                                    <td>
                                                        <span class="badge bg-{{ $driver->fleet->status_color }}">
                                                            {{ ucfirst($driver->fleet->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        @else
                                            <div class="text-center py-4 text-muted">
                                                <i class="bi bi-truck display-6"></i>
                                                <p class="mt-2">No fleet assigned</p>
                                                <a href="{{ route('partners.drivers.edit', $driverId) }}" 
                                                   class="btn btn-sm btn-primary mt-2">
                                                    <i class="bi bi-truck me-1"></i>
                                                    Assign Fleet
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Important Information -->
                    <div class="col-lg-4">
                        <!-- License Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-card-checklist me-2"></i>
                                    License Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="license-number">
                                        <div class="fw-bold">{{ $driver->driving_license_number }}</div>
                                        <small class="text-muted">License Number</small>
                                    </div>
                                </div>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th>Class:</th>
                                        <td>{{ $driver->license_class }}</td>
                                    </tr>
                                    <tr>
                                        <th>Issue Date:</th>
                                        <td>{{ $driver->driving_license_issue_date?->format('M d, Y') ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Expiry Date:</th>
                                        <td>{{ $driver->driving_license_expiry_date?->format('M d, Y') ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @php
                                                $licenseValidity = $this->calculateLicenseValidity();
                                            @endphp
                                            <span class="badge bg-{{ $licenseValidity['color'] }}">
                                                <i class="bi bi-{{ $licenseValidity['icon'] }} me-1"></i>
                                                {{ $licenseValidity['text'] }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Certifications -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-shield-check me-2"></i>
                                    Certifications
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Medical Certificate -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-medium">Medical Certificate</span>
                                        @php
                                            $medicalValidity = $this->calculateMedicalCertificateValidity();
                                        @endphp
                                        <span class="badge bg-{{ $medicalValidity['color'] }}">
                                            {{ $medicalValidity['text'] }}
                                        </span>
                                    </div>
                                    @if($driver->has_medical_certificate && $driver->medical_certificate_expiry)
                                        <small class="text-muted">
                                            Expires: {{ $driver->medical_certificate_expiry->format('M d, Y') }}
                                        </small>
                                    @endif
                                </div>

                                <!-- First Aid Training -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-medium">First Aid Training</span>
                                        @php
                                            $firstAidValidity = $this->calculateFirstAidValidity();
                                        @endphp
                                        <span class="badge bg-{{ $firstAidValidity['color'] }}">
                                            {{ $firstAidValidity['text'] }}
                                        </span>
                                    </div>
                                    @if($driver->has_first_aid_training && $driver->first_aid_training_expiry)
                                        <small class="text-muted">
                                            Expires: {{ $driver->first_aid_training_expiry->format('M d, Y') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-telephone-plus me-2"></i>
                                    Emergency Contact
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($driver->emergency_contact_name)
                                    <div class="mb-3">
                                        <div class="fw-bold">{{ $driver->emergency_contact_name }}</div>
                                        <small class="text-muted">{{ $driver->emergency_contact_relationship }}</small>
                                    </div>
                                    <div class="contact-info">
                                        <div class="mb-2">
                                            <i class="bi bi-telephone me-2 text-muted"></i>
                                            <a href="tel:{{ $driver->emergency_contact_phone }}" class="text-decoration-none">
                                                {{ $driver->emergency_contact_phone }}
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-2 text-muted">
                                        <i class="bi bi-telephone display-6"></i>
                                        <p class="mt-2">No emergency contact set</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Banking Information -->
                        @if($driver->bank_name || $driver->bank_account_number)
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="bi bi-bank me-2"></i>
                                        Banking Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        @if($driver->bank_name)
                                            <tr>
                                                <th>Bank:</th>
                                                <td>{{ $driver->bank_name }}</td>
                                            </tr>
                                        @endif
                                        @if($driver->bank_account_number)
                                            <tr>
                                                <th>Account No:</th>
                                                <td>{{ $driver->bank_account_number }}</td>
                                            </tr>
                                        @endif
                                        @if($driver->bank_account_name)
                                            <tr>
                                                <th>Account Name:</th>
                                                <td>{{ $driver->bank_account_name }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Trips Tab -->
            @if($activeTab === 'trips')
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-map me-2"></i>
                            Trip History
                        </h5>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" style="width: auto;" 
                                    wire:model.live="tripStatusFilter">
                                @foreach($tripStatuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control form-control-sm" style="width: 200px;"
                                   placeholder="Date range" id="tripDateRange">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-5">
                            <i class="bi bi-map display-1 text-muted"></i>
                            <h4 class="mt-3">No Trips Available</h4>
                            <p class="text-muted">Trip tracking feature will be available soon.</p>
                        </div>
                        <!-- Uncomment when Trip model is ready -->
                        <!--
                        @if($recentTrips->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Trip ID</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Distance</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentTrips as $trip)
                                            <tr>
                                                <td><code>{{ $trip->trip_code ?? $trip->id }}</code></td>
                                                <td>{{ Str::limit($trip->pickup_location, 30) }}</td>
                                                <td>{{ Str::limit($trip->dropoff_location, 30) }}</td>
                                                <td>{{ number_format($trip->distance_km, 1) }} km</td>
                                                <td>{{ $trip->duration_minutes }} mins</td>
                                                <td>
                                                    <span class="badge bg-{{ $trip->status === 'completed' ? 'success' : ($trip->status === 'in_progress' ? 'primary' : 'secondary') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $trip->status)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $trip->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <a href="{{ route('partners.trips.view', $trip->id) }}" 
                                                       class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $recentTrips->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-map display-1 text-muted"></i>
                                <h4 class="mt-3">No Trips Found</h4>
                                <p class="text-muted">This driver hasn't completed any trips yet.</p>
                            </div>
                        @endif
                        -->
                    </div>
                </div>
            @endif

            <!-- Deliveries Tab -->
            @if($activeTab === 'deliveries')
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-box-seam me-2"></i>
                            Delivery History
                        </h5>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" style="width: auto;" 
                                    wire:model.live="ratingFilter">
                                @foreach($ratingOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="delivery-stats mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="stat-item text-center">
                                        <div class="stat-value text-success">{{ $performanceStats['successful_deliveries'] }}</div>
                                        <div class="stat-label">Successful</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item text-center">
                                        <div class="stat-value text-danger">{{ $performanceStats['failed_deliveries'] }}</div>
                                        <div class="stat-label">Failed</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item text-center">
                                        <div class="stat-value text-warning">{{ $performanceStats['cancelled_deliveries'] }}</div>
                                        <div class="stat-label">Cancelled</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stat-item text-center">
                                        <div class="stat-value text-primary">{{ $performanceStats['success_rate'] }}%</div>
                                        <div class="stat-label">Success Rate</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center py-3">
                            <i class="bi bi-box-seam display-1 text-muted"></i>
                            <h4 class="mt-3">Delivery Tracking Coming Soon</h4>
                            <p class="text-muted">Detailed delivery history will be available in the next update.</p>
                        </div>
                        <!-- Uncomment when Delivery model is ready -->
                        <!--
                        @if($recentDeliveries->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Delivery ID</th>
                                            <th>Recipient</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <th>Rating</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentDeliveries as $delivery)
                                            <tr>
                                                <td><code>{{ $delivery->tracking_number ?? $delivery->id }}</code></td>
                                                <td>{{ $delivery->recipient_name }}</td>
                                                <td>{{ Str::limit($delivery->delivery_address, 30) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $delivery->status === 'delivered' ? 'success' : ($delivery->status === 'in_transit' ? 'primary' : 'secondary') }}">
                                                        {{ ucfirst($delivery->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($delivery->rating)
                                                        <div class="star-rating">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="bi bi-star{{ $i <= $delivery->rating ? '-fill' : '' }} text-warning"></i>
                                                            @endfor
                                                        </div>
                                                    @else
                                                        <span class="text-muted">No rating</span>
                                                    @endif
                                                </td>
                                                <td>{{ $delivery->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <a href="{{ route('partners.deliveries.view', $delivery->id) }}" 
                                                       class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $recentDeliveries->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-box-seam display-1 text-muted"></i>
                                <h4 class="mt-3">No Deliveries Found</h4>
                                <p class="text-muted">This driver hasn't completed any deliveries yet.</p>
                            </div>
                        @endif
                        -->
                    </div>
                </div>
            @endif

            <!-- Documents Tab -->
            @if($activeTab === 'documents')
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-files me-2"></i>
                            Documents
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(!empty($documents))
                            <div class="row">
                                @foreach($documents as $index => $document)
                                    <div class="col-md-4 col-lg-3 mb-4">
                                        <div class="document-card">
                                            <div class="document-icon">
                                                @if(isset($document['type']) && str_contains($document['type'], 'pdf'))
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                @elseif(isset($document['type']) && str_contains($document['type'], 'image'))
                                                    <i class="bi bi-file-earmark-image"></i>
                                                @elseif(isset($document['type']) && str_contains($document['type'], 'word'))
                                                    <i class="bi bi-file-earmark-word"></i>
                                                @else
                                                    <i class="bi bi-file-earmark"></i>
                                                @endif
                                            </div>
                                            <div class="document-info">
                                                <h6 class="document-title">{{ $document['name'] ?? 'Document ' . ($index + 1) }}</h6>
                                                <small class="text-muted">
                                                    @if(isset($document['uploaded_at']))
                                                        {{ \Carbon\Carbon::parse($document['uploaded_at'])->format('M d, Y') }}
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="document-actions">
                                                @if(isset($document['path']) && Storage::disk('public')->exists($document['path']))
                                                    <a href="{{ Storage::url($document['path']) }}" 
                                                       target="_blank" class="btn btn-sm btn-outline-info" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ Storage::url($document['path']) }}" 
                                                       download class="btn btn-sm btn-outline-success" title="Download">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-files display-1 text-muted"></i>
                                <h4 class="mt-3">No Documents</h4>
                                <p class="text-muted">No documents have been uploaded for this driver.</p>
                                <a href="{{ route('partners.drivers.edit', $driverId) }}" 
                                   class="btn btn-primary">
                                    <i class="bi bi-upload me-2"></i>
                                    Upload Documents
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Performance Tab -->
            @if($activeTab === 'performance')
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-bar-chart me-2"></i>
                            Performance Analytics
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Performance Summary -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="performance-card">
                                    <div class="performance-icon success">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div class="performance-content">
                                        <div class="performance-value">{{ $performanceStats['success_rate'] }}%</div>
                                        <div class="performance-label">Success Rate</div>
                                        <div class="performance-subtext">
                                            {{ $performanceStats['successful_deliveries'] }} successful out of {{ $performanceStats['total_deliveries'] }} deliveries
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="performance-card">
                                    <div class="performance-icon warning">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div class="performance-content">
                                        <div class="performance-value">{{ $performanceStats['on_time_rate'] }}%</div>
                                        <div class="performance-label">On-Time Rate</div>
                                        <div class="performance-subtext">
                                            Average delivery time: {{ $performanceStats['average_delivery_time'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rating Summary -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Rating Summary</h6>
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    <div class="rating-display">
                                        <div class="rating-value">{{ number_format($performanceStats['rating'], 1) }}</div>
                                        <div class="star-rating-large">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $performanceStats['rating'] ? '-fill' : ($i - 0.5 <= $performanceStats['rating'] ? '-half' : '') }}"></i>
                                            @endfor
                                        </div>
                                        <div class="rating-count">Based on 0 ratings</div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <!-- Rating distribution chart would go here -->
                                    <div class="text-center py-4 text-muted">
                                        <i class="bi bi-bar-chart display-4"></i>
                                        <p class="mt-2">Rating distribution chart will be available soon</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Performance Metrics -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Key Performance Indicators</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="metric-card">
                                        <div class="metric-icon">
                                            <i class="bi bi-speedometer2"></i>
                                        </div>
                                        <div class="metric-content">
                                            <div class="metric-value">{{ $performanceStats['total_distance'] }}</div>
                                            <div class="metric-label">Total Distance Covered</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="metric-card">
                                        <div class="metric-icon">
                                            <i class="bi bi-calendar-check"></i>
                                        </div>
                                        <div class="metric-content">
                                            <div class="metric-value">{{ $this->getEmploymentDuration() }}</div>
                                            <div class="metric-label">Employment Duration</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="metric-card">
                                        <div class="metric-icon">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </div>
                                        <div class="metric-content">
                                            <div class="metric-value">{{ $performanceStats['total_deliveries'] }}</div>
                                            <div class="metric-label">Total Deliveries Completed</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        @if($driver->notes)
                            <div class="mt-4">
                                <h6 class="border-bottom pb-2 mb-3">Additional Notes</h6>
                                <div class="p-3 bg-light rounded">
                                    {{ $driver->notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .dashboard-section {
            padding: 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .section-subtitle {
            color: var(--text-light);
            margin: 5px 0 0 0;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .driver-avatar .profile-photo-lg {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 4px solid var(--primary-color);
        }

        .driver-avatar .avatar-placeholder-lg {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 2rem;
            border: 4px solid #e9ecef;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        .status-secondary {
            background-color: rgba(108, 117, 125, 0.1);
            color: #495057;
            border: 1px solid rgba(108, 117, 125, 0.2);
        }

        .status-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #721c24;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .status-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        .status-dark {
            background-color: rgba(52, 58, 64, 0.1);
            color: #343a40;
            border: 1px solid rgba(52, 58, 64, 0.2);
        }

        /* Quick Stats */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 15px;
            height: 100%;
        }

        .stat-card .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #6f42c1, #6610f2);
        }

        .stat-icon.distance {
            background: linear-gradient(135deg, #20c997, #17a2b8);
        }

        .stat-icon.rating {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }

        .stat-icon.experience {
            background: linear-gradient(135deg, #6c757d, #495057);
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: 5px;
        }

        .stat-subtext {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-top: 2px;
        }

        /* Tabs */
        .tabs-navigation .nav-tabs {
            border-bottom: 2px solid var(--border-color);
        }

        .tabs-navigation .nav-link {
            border: none;
            color: var(--text-light);
            font-weight: 500;
            padding: 12px 20px;
        }

        .tabs-navigation .nav-link.active {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            background: transparent;
        }

        /* Table Styling */
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--text-dark);
            border-bottom: 2px solid var(--border-color);
        }

        .table-borderless th,
        .table-borderless td {
            border: none;
            padding: 8px 0;
        }

        /* Avatar */
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .avatar.bg-primary { background-color: #007bff; }
        .avatar.bg-success { background-color: #28a745; }

        /* Delivery Stats */
        .delivery-stats .stat-item {
            padding: 15px;
        }

        .delivery-stats .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .delivery-stats .stat-label {
            font-size: 0.9rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Document Cards */
        .document-card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .document-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .document-icon {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .document-info {
            margin-bottom: 15px;
        }

        .document-title {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 5px;
            word-break: break-word;
        }

        .document-actions {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        /* Performance Cards */
        .performance-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .performance-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .performance-icon.success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .performance-icon.warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }

        .performance-content {
            flex: 1;
        }

        .performance-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
        }

        .performance-label {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: 5px;
        }

        .performance-subtext {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-top: 2px;
        }

        /* Rating Display */
        .rating-display {
            padding: 20px;
        }

        .rating-value {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
        }

        .star-rating-large {
            font-size: 1.5rem;
            color: #ffc107;
            margin: 10px 0;
        }

        .rating-count {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        /* Metric Cards */
        .metric-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .metric-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6c757d, #495057);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .metric-content {
            flex: 1;
        }

        .metric-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
        }

        .metric-label {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-top: 2px;
        }

        /* License Number */
        .license-number {
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        /* Star Rating */
        .star-rating {
            color: #ffc107;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .section-header {
                flex-direction: column;
                align-items: stretch;
            }

            .header-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .tabs-navigation .nav-link {
                padding: 8px 12px;
                font-size: 0.9rem;
            }

            .stat-card {
                flex-direction: column;
                text-align: center;
                padding: 15px;
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .performance-card {
                flex-direction: column;
                text-align: center;
            }

            .metric-card {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <script>
        function confirmDelete(driverName, deleteUrl) {
            if (confirm(`Are you sure you want to delete "${driverName}"? This action cannot be undone. All related trips and assignments will also be deleted.`)) {
                window.location.href = deleteUrl;
            }
        }

        // Initialize date range picker for trips
        document.addEventListener('DOMContentLoaded', function() {
            const dateRangeInput = document.getElementById('tripDateRange');
            if (dateRangeInput) {
                // Initialize date range picker (you can use a library like flatpickr)
                // For now, it's just a placeholder
                dateRangeInput.addEventListener('change', function(e) {
                    @this.set('dateRange', e.target.value);
                });
            }

            // Auto-hide flash messages after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('[wire\\:initial-data]');
                @this.dispatch('message-cleared');
            }, 5000);
        });

        // Tab navigation
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href') === '#') {
                    e.preventDefault();
                }
            });
        });
    </script>
</div></div>
