<div>
    <div>
        <div class="row">
            <div class="col-md-4">
                <!-- Profile Card -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-user-circle mr-2"></i>Assistant Profile
                        </h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto"
                                style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-3x text-primary"></i>
                            </div>
                        </div>

                        <h4 class="mb-1">{{ $assistant->full_name }}</h4>
                        <p class="text-muted mb-3">
                            <i class="fas fa-id-badge mr-1"></i>
                            {{ $assistant->id_number }}
                        </p>

                        @php
                            $statusBadge = $this->getStatusBadge($assistant->status);
                        @endphp
                        <span class="badge badge-{{ $statusBadge['color'] }} badge-lg mb-3 px-3 py-2">
                            <i class="fas {{ $statusBadge['icon'] }} mr-2"></i>
                            {{ $statusBadge['text'] }}
                        </span>

                        <div class="list-group list-group-flush mt-3">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-user-tag mr-2 text-info"></i>Role</span>
                                <span class="badge badge-info">{{ ucfirst($assistant->role) }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-envelope mr-2 text-primary"></i>Email</span>
                                <span>{{ $assistant->email }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-phone mr-2 text-success"></i>Phone</span>
                                <span>{{ $assistant->phone_number }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calendar-alt mr-2 text-secondary"></i>Joined</span>
                                <span>{{ $assistant->created_at }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-user-plus mr-2 text-info"></i>Added By</span>
                                <span>{{ $assistant->creator->name ?? 'System' }}</span>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-4">
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('admin.pha.edit', $assistant->id) }}"
                                    class="btn btn-warning">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                @if ($assistant->status === 'active')
                                    <button type="button" class="btn btn-danger" wire:click="suspendAssistant"
                                        wire:confirm="Are you sure you want to suspend this assistant?">
                                        <i class="fas fa-ban mr-1"></i>Suspend
                                    </button>
                                @elseif($assistant->status === 'suspended')
                                    <button type="button" class="btn btn-success" wire:click="activateAssistant">
                                        <i class="fas fa-check mr-1"></i>Activate
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success" wire:click="toggleAssistantStatus">
                                        <i class="fas fa-toggle-on mr-1"></i>Activate
                                    </button>
                                @endif
                            </div>
                            <a href="{{ route('admin.pha.index') }}"
                                class="btn btn-outline-secondary btn-block mt-2">
                                <i class="fas fa-arrow-left mr-1"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Account Info -->
                @if ($assistant->user)
                    <div class="card mt-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user-shield mr-2"></i>User Account
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user-check fa-2x text-success mr-3"></i>
                                <div>
                                    <h6 class="mb-1">Account Status:
                                        <span
                                            class="badge badge-{{ $assistant->user->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($assistant->user->status) }}
                                        </span>
                                    </h6>
                                    <small class="text-muted">Username: {{ $assistant->user->user_name }}</small>
                                </div>
                            </div>

                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-sign-in-alt mr-2"></i>Last Login</span>
                                    <span>{{ $assistant->user->last_login_at ? $assistant->user->last_login_at : 'Never' }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-clock mr-2"></i>Last Activity</span>
                                    <span>{{ $assistant->last_activity_at ? $assistant->last_activity_at : 'Never' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-8">
                <!-- Tabs Navigation -->
                <div class="card">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab === 'profile' ? 'active' : '' }}"
                                    wire:click="setActiveTab('profile')">
                                    <i class="fas fa-id-card mr-2"></i>Profile Details
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab === 'employment' ? 'active' : '' }}"
                                    wire:click="setActiveTab('employment')">
                                    <i class="fas fa-building mr-2"></i>Employment History
                                    <span class="badge badge-light ml-1">{{ 00000 }}</span>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab === 'activity' ? 'active' : '' }}"
                                    wire:click="setActiveTab('activity')">
                                    <i class="fas fa-history mr-2"></i>Activity Log
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <!-- Profile Tab -->
                        @if ($activeTab === 'profile')
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-info-circle mr-2"></i>Basic Information
                                    </h5>
                                    <dl class="row">
                                        <dt class="col-sm-4">Full Name:</dt>
                                        <dd class="col-sm-8">{{ $assistant->full_name }}</dd>

                                        <dt class="col-sm-4">ID Number:</dt>
                                        <dd class="col-sm-8">
                                            <code>{{ $assistant->id_number }}</code>
                                        </dd>

                                        <dt class="col-sm-4">Email:</dt>
                                        <dd class="col-sm-8">
                                            <a href="mailto:{{ $assistant->email }}">{{ $assistant->email }}</a>
                                        </dd>

                                        <dt class="col-sm-4">Phone:</dt>
                                        <dd class="col-sm-8">
                                            <a
                                                href="tel:{{ $assistant->phone_number }}">{{ $assistant->phone_number }}</a>
                                        </dd>

                                        <dt class="col-sm-4">Role:</dt>
                                        <dd class="col-sm-8">
                                            <span class="badge badge-info">{{ ucfirst($assistant->role) }}</span>
                                        </dd>
                                    </dl>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-calendar-alt mr-2"></i>Timeline
                                    </h5>
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Account Created</h6>
                                                <p class="text-muted mb-0">
                                                    {{ $assistant->created_at }}
                                                    <br>
                                                    <small>by {{ $assistant->creator->name ?? 'System' }}</small>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Last Updated</h6>
                                                <p class="text-muted mb-0">
                                                    {{ $assistant->updated_at }}
                                                </p>
                                            </div>
                                        </div>

                                        @if ($assistant->last_login_at)
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-info"></div>
                                                <div class="timeline-content">
                                                    <h6 class="mb-1">Last Login</h6>
                                                    <p class="text-muted mb-0">
                                                        {{ $assistant->last_login_at }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($assistant->last_activity_at)
                                            <div class="timeline-item">
                                                <div class="timeline-marker bg-warning"></div>
                                                <div class="timeline-content">
                                                    <h6 class="mb-1">Last Activity</h6>
                                                    <p class="text-muted mb-0">
                                                        {{ $assistant->last_activity_at }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Employment Tab -->
                        @if ($activeTab === 'employment')
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="text-primary mb-0">
                                    <i class="fas fa-building mr-2"></i>Station Assignments
                                </h5>
                                <button class="btn btn-primary btn-sm" wire:click="showAssignStationModal">
                                    <i class="fas fa-plus mr-1"></i>Assign New Station
                                </button>
                            </div>

                        @endif

                        <!-- Activity Tab -->
                        @if ($activeTab === 'activity')
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-history mr-2"></i>Recent Activity
                            </h5>

                            <div class="timeline">
                                <!-- Example activities - you would typically fetch these from an activity log -->
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1">Profile Updated</h6>
                                            <small class="text-muted">2 hours ago</small>
                                        </div>
                                        <p class="text-muted mb-0">Assistant details were modified</p>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1">Station Assigned</h6>
                                            <small class="text-muted">1 day ago</small>
                                        </div>
                                        <p class="text-muted mb-0">Assigned to Downtown Station</p>
                                    </div>
                                </div>

                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1">Account Created</h6>
                                            <small class="text-muted">1 week ago</small>
                                        </div>
                                        <p class="text-muted mb-0">Assistant profile was created by Admin</p>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Note:</strong> Activity tracking requires implementation of an activity log
                                system.
                                Consider using packages like Spatie Activitylog or implementing your own logging system.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Days Active</span>
                                <span class="info-box-number">
                                    {{ $assistant->created_at->diffInDays(now()) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-calendar-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Last Updated</span>
                                <span class="info-box-number">
                                    {{ $assistant->updated_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assign Station Modal -->
        @if ($showEmploymentModal)
            <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);"
                role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Assign Station to Assistant</h5>
                            <button type="button" class="close" wire:click="$set('showEmploymentModal', false)">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-4">
                                Assigning station to:
                                <strong>{{ $assistant->full_name }}</strong>
                            </p>

                            <div class="form-group">
                                <label for="selectedStation">Select Station *</label>
                                <select id="selectedStation" class="form-control" wire:model="selectedStation">
                                    <option value="">-- Choose a Station --</option>
                                    @foreach (\App\Models\StationPartner::where('is_active', true)->orderBy('name')->get() as $station)
                                        <option value="{{ $station->id }}">
                                            {{ $station->name }}
                                            @if ($station->location)
                                                - {{ $station->location }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('selectedStation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="employmentStatus">Assignment Status *</label>
                                <select id="employmentStatus" class="form-control" wire:model="employmentStatus">
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                    <option value="pending">Pending</option>
                                </select>
                                @error('employmentStatus')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            @if ($selectedStation)
                                @php
                                    $existing = $assistant->employments
                                        ->where('station_partner_id', $selectedStation)
                                        ->first();
                                @endphp
                                @if ($existing)
                                    <div class="alert alert-warning mt-3">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        This assistant is already assigned to this station.
                                        Updating will modify the existing assignment.
                                    </div>
                                @endif
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('showEmploymentModal', false)">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-primary" wire:click="assignStation"
                                @if (!$selectedStation) disabled @endif>
                                <i class="fas fa-building mr-1"></i>
                                {{ $selectedStation && $assistant->employments->where('station_partner_id', $selectedStation)->first() ? 'Update Assignment' : 'Assign Station' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @push('styles')
            <style>
                .badge-lg {
                    font-size: 1rem;
                    padding: 0.5rem 1rem;
                }

                .info-box {
                    background: #fff;
                    border-radius: 0.25rem;
                    padding: 15px;
                    box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
                    margin-bottom: 0;
                }

                .info-box-icon {
                    border-radius: 0.25rem;
                    float: left;
                    height: 60px;
                    width: 60px;
                    text-align: center;
                    font-size: 25px;
                    line-height: 60px;
                    background: rgba(0, 0, 0, 0.2);
                }

                .info-box-content {
                    padding: 5px 10px;
                    margin-left: 70px;
                }

                .info-box-text {
                    text-transform: uppercase;
                    font-weight: 600;
                    font-size: 12px;
                    color: #6c757d;
                }

                .info-box-number {
                    font-weight: 700;
                    font-size: 18px;
                    color: #343a40;
                }

                .timeline {
                    position: relative;
                    padding-left: 40px;
                }

                .timeline::before {
                    content: '';
                    position: absolute;
                    left: 15px;
                    top: 0;
                    bottom: 0;
                    width: 2px;
                    background: #e9ecef;
                }

                .timeline-item {
                    position: relative;
                    margin-bottom: 20px;
                }

                .timeline-marker {
                    position: absolute;
                    left: -40px;
                    top: 5px;
                    width: 20px;
                    height: 20px;
                    border-radius: 50%;
                    border: 3px solid #fff;
                    box-shadow: 0 0 0 3px #e9ecef;
                }

                .timeline-content {
                    padding-left: 10px;
                }

                .nav-tabs .nav-link {
                    border-top-left-radius: 0;
                    border-top-right-radius: 0;
                    color: #6c757d;
                    font-weight: 500;
                }

                .nav-tabs .nav-link.active {
                    color: #495057;
                    background-color: #fff;
                    border-color: #dee2e6 #dee2e6 #fff;
                }
            </style>
        @endpush
    </div>
</div>
