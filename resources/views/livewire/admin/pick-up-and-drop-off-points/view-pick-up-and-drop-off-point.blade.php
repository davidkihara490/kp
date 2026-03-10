<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-bus mr-2"></i>pickUpAndDropOffPoint Details
            </h3>
            <div class="card-tools">
                <span class="badge badge-{{ $this->getStatusColor() }}">
                    <i class="fas {{ $this->getStatusIcon() }} mr-1"></i>
                    {{ ucfirst($pickUpAndDropOffPoint->status) }}
                </span>
            </div>
        </div>
        
        <div class="card-body">
            @include('components.alerts.response-alerts')

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-box"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Parcels</span>
                            <span class="info-box-number">{{ $parcelsCount }}</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-calendar-day"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Today's Parcels</span>
                            <span class="info-box-number">{{ $todayParcels }}</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-calendar-week"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">This Week</span>
                            <span class="info-box-number">{{ $weeklyParcels }}</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">This Month</span>
                            <span class="info-box-number">{{ $monthlyParcels }}</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column: pickUpAndDropOffPoint Information -->
                <div class="col-md-8">
                    <!-- Basic Information Card -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-2"></i>Basic Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>pickUpAndDropOffPoint Name:</strong></p>
                                    <p class="text-muted">{{ $pickUpAndDropOffPoint->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>pickUpAndDropOffPoint ID:</strong></p>
                                    <p class="text-muted">#{{ $pickUpAndDropOffPoint->id }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Building:</strong></p>
                                    <p class="text-muted">{{ $pickUpAndDropOffPoint->building ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Room Number:</strong></p>
                                    <p class="text-muted">{{ $pickUpAndDropOffPoint->room_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <p><strong>Address:</strong></p>
                                    <p class="text-muted">{{ $pickUpAndDropOffPoint->address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information Card -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-map-marker-alt mr-2"></i>Location Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Town:</strong></p>
                                    <p class="text-muted">
                                        {{ $pickUpAndDropOffPoint->town->name ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>pickUpAndDropOffPoint Partner:</strong></p>
                                    <p class="text-muted">
                                        @if($pickUpAndDropOffPoint->pickUpAndDropOffPointPartner)
                                            <span class="badge badge-info">{{ $pickUpAndDropOffPoint->pickUpAndDropOffPointPartner->name }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Operating Hours Card -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clock mr-2"></i>Operating Hours
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Opening Hours:</strong></p>
                                    <p class="text-muted">
                                        <i class="fas fa-door-open mr-2"></i>
                                        {{ $this->formatTime($pickUpAndDropOffPoint->opening_hours) }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Closing Hours:</strong></p>
                                    <p class="text-muted">
                                        <i class="fas fa-door-closed mr-2"></i>
                                        {{ $this->formatTime($pickUpAndDropOffPoint->closing_hours) }}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="progress" style="height: 20px;">
                                        @php
                                            $progress = $this->getOperatingHoursProgress();
                                        @endphp
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ min($progress, 100) }}%"
                                             aria-valuenow="{{ $progress }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ now()->format('h:i A') }}
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        @if($this->isCurrentlyOpen())
                                            <span class="text-success">
                                                <i class="fas fa-check-circle"></i> Currently Open
                                            </span>
                                        @else
                                            <span class="text-danger">
                                                <i class="fas fa-times-circle"></i> Currently Closed
                                            </span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Contact & Actions -->
                <div class="col-md-4">
                    <!-- Contact Information Card -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-address-book mr-2"></i>Contact Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Contact Person:</strong></p>
                            <p class="text-muted">
                                <i class="fas fa-user mr-2"></i>{{ $pickUpAndDropOffPoint->contact_person }}
                            </p>
                            
                            <p><strong>Email:</strong></p>
                            <p class="text-muted">
                                <i class="fas fa-envelope mr-2"></i>
                                <a href="mailto:{{ $pickUpAndDropOffPoint->contact_email }}">{{ $pickUpAndDropOffPoint->contact_email }}</a>
                            </p>
                            
                            <p><strong>Phone:</strong></p>
                            <p class="text-muted">
                                <i class="fas fa-phone mr-2"></i>
                                <a href="tel:{{ $pickUpAndDropOffPoint->contact_phone_number }}">{{ $pickUpAndDropOffPoint->contact_phone_number }}</a>
                            </p>
                        </div>
                    </div>

                    <!-- Quick Actions Card -->
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bolt mr-2"></i>Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.points.edit', $pickUpAndDropOffPoint->id) }}" 
                                   class="btn btn-warning btn-block">
                                    <i class="fas fa-edit mr-2"></i>Edit pickUpAndDropOffPoint
                                </a>
                                
                                <button type="button" class="btn btn-{{ $pickUpAndDropOffPoint->status === 'active' ? 'danger' : 'success' }} btn-block"
                                        wire:click="toggleStatus"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="toggleStatus">
                                        <i class="fas fa-power-off mr-2"></i>
                                        {{ $pickUpAndDropOffPoint->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </span>
                                    <span wire:loading wire:target="toggleStatus">
                                        <span class="spinner-border spinner-border-sm" role="status"></span>
                                        Updating...
                                    </span>
                                </button>
                                
                                <button type="button" class="btn btn-danger btn-block"
                                        wire:click="confirmDelete"
                                        wire:loading.attr="disabled">
                                    <i class="fas fa-trash mr-2"></i>Delete pickUpAndDropOffPoint
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Audit Information Card -->
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history mr-2"></i>Audit Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Created:</strong></p>
                            <p class="text-muted">
                                <i class="far fa-calendar-plus mr-2"></i>
                                {{ $pickUpAndDropOffPoint->created_at->format('M d, Y') }}
                                <br>
                                <small>{{ $pickUpAndDropOffPoint->created_at->format('h:i A') }}</small>
                            </p>
                            
                            <p><strong>Last Updated:</strong></p>
                            <p class="text-muted">
                                <i class="far fa-calendar-check mr-2"></i>
                                {{ $pickUpAndDropOffPoint->updated_at->format('M d, Y') }}
                                <br>
                                <small>{{ $pickUpAndDropOffPoint->updated_at->format('h:i A') }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity (Placeholder) -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-2"></i>Recent Activity
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                Activity tracking will be implemented when Parcel model is available.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('admin.points.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Back to pickUpAndDropOffPoints
                    </a>
                    <a href="{{ route('admin.points.create') }}" class="btn btn-success">
                        <i class="fas fa-plus mr-2"></i>Add New pickUpAndDropOffPoint
                    </a>
                </div>
                <div class="col-md-6 text-right">
                    <span class="text-muted">
                        Last updated: {{ $pickUpAndDropOffPoint->updated_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .info-box {
            min-height: 90px;
            margin-bottom: 10px;
        }
        .info-box-icon {
            padding-top: 25px;
        }
        .card {
            margin-bottom: 20px;
        }
        .progress {
            margin-top: 5px;
        }
        .text-muted {
            color: #6c757d !important;
        }
        .d-grid {
            display: grid;
        }
        .gap-2 {
            gap: 10px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Listen for delete confirmation
            Livewire.on('confirm-delete', (event) => {
                if (confirm(event.message)) {
                    @this.delete();
                }
            });

            // Auto-refresh statistics every 30 seconds
            setInterval(() => {
                @this.loadStatistics();
            }, 30000);
        });
    </script>
@endpush