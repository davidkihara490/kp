<div>
    <div>
        <div class="dashboard-section">
            <div class="section-header">
                <div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="point-icon-large">
                            <i class="bi {{ $this->getTypeIcon() }}"></i>
                        </div>
                        <div>
                            <h3 class="section-title mb-1">{{ $point->name }}</h3>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge {{ $this->getTypeBadgeClass() }}">
                                    <i class="bi {{ $this->getTypeIcon() }} me-1"></i>
                                    {{ ucfirst($point->type) }} Point
                                </span>
                                <span class="status-badge {{ $this->getStatusBadgeClass() }}">
                                    <i
                                        class="bi {{ $point->status === 'active' ? 'bi-check-circle' : ($point->status === 'maintenance' ? 'bi-tools' : 'bi-pause-circle') }} me-1"></i>
                                    {{ ucfirst($point->status) }}
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-upc-scan me-1"></i>
                                    {{ $point->code }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <p class="section-subtitle mt-2">
                        <i class="bi bi-geo-alt me-1"></i>
                        {{ $point->address }}, {{ $town->name ?? 'Unknown' }}
                    </p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-outline-secondary" wire:click="$dispatch('closeView')">
                        <i class="bi bi-arrow-left me-2"></i>
                        Back
                    </button>

                    <a href="{{ route('partners.pd.edit', $point->id) }}"
                        class="btn btn-outline-primary">Edit</a>
                    @if ($point->status === 'active')
                    <button class="btn btn-outline-warning" wire:click="confirmDeactivate">
                        <i class="bi bi-pause-circle me-2"></i>
                        Deactivate
                    </button>
                    @else
                    <button class="btn btn-outline-success" wire:click="toggleStatus">
                        <i class="bi bi-play-circle me-2"></i>
                        Activate
                    </button>
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">{{ $parcels_today }}</div>
                                    <div class="stat-label">Parcels Today</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="bi bi-calendar-week"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-value">{{ $parcels_this_week }}</div>
                                    <div class="stat-label">This Week</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Information Cards -->
                    <div class="row">
                        <!-- Contact Information -->
                        <div class="col-md-6 mb-4">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <h5><i class="bi bi-person-lines-fill me-2"></i>Contact Information</h5>
                                </div>
                                <div class="info-card-body">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-person"></i>
                                            Contact Person
                                        </div>
                                        <div class="info-value">{{ $point->contact_person }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-envelope"></i>
                                            Email
                                        </div>
                                        <div class="info-value">
                                            <a
                                                href="mailto:{{ $point->contact_email }}">{{ $point->contact_email }}</a>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-telephone"></i>
                                            Phone
                                        </div>
                                        <div class="info-value">
                                            <a href="tel:{{ $point->contact_phone }}">{{ $point->contact_phone }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="col-md-6 mb-4">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <h5><i class="bi bi-geo me-2"></i>Location Details</h5>
                                </div>
                                <div class="info-card-body">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-house-door"></i>
                                            Address
                                        </div>
                                        <div class="info-value">{{ $point->address }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-building"></i>
                                            Town/City
                                        </div>
                                        <div class="info-value">{{ $town->name ?? 'Not specified' }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-shop"></i>
                                            Associated Station
                                        </div>
                                        <div class="info-value">
                                            @if ($station)
                                            <a href="#"
                                                wire:click="$dispatch('viewStation', {{ $station->id }})">
                                                {{ $station->name }}
                                            </a>
                                            @else
                                            <span class="text-muted">None</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($point->latitude && $point->longitude)
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-globe"></i>
                                            Coordinates
                                        </div>
                                        <div class="info-value">
                                            {{ $point->latitude }}, {{ $point->longitude }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Operating Hours -->
                        <div class="col-md-6 mb-4">
                            <div class="info-card">
                                <div class="info-card-header">
                                    <h5><i class="bi bi-clock me-2"></i>Operating Hours</h5>
                                </div>
                                <div class="info-card-body">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-calendar-week"></i>
                                            Operating Days
                                        </div>
                                        @php
                                        $operatingDays = json_decode($point->operating_days, true);
                                        @endphp

                                        <table class="table table-sm">
                                            <tbody>
                                                @foreach($operatingDays as $day => $hours)
                                                <tr>
                                                    <td width="100"><strong>{{ $day }}</strong></td>
                                                    <td>
                                                        @if($hours['closed'])
                                                        <span class="badge bg-secondary">Closed</span>
                                                        @else
                                                        {{ date('h:i A', strtotime($hours['opening'])) }} -
                                                        {{ date('h:i A', strtotime($hours['closing'])) }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="col-md-6 mb-4">
                        <div class="info-card">
                            <div class="info-card-header">
                                <h5><i class="bi bi-card-text me-2"></i>Additional Information</h5>
                            </div>
                            <div class="info-card-body">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-box-seam"></i>
                                        Storage Capacity
                                    </div>
                                    <div class="info-value">
                                        {{ $point->capacity ? $point->capacity . ' parcels' : 'Not specified' }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-calendar"></i>
                                        Created
                                    </div>
                                    <div class="info-value">
                                        {{ $point->created_at->format('M d, Y') }}
                                        <small
                                            class="text-muted d-block">({{ $point->created_at->diffForHumans() }})</small>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-clock-history"></i>
                                        Last Updated
                                    </div>
                                    <div class="info-value">
                                        {{ $point->updated_at->format('M d, Y') }}
                                        <small
                                            class="text-muted d-block">({{ $point->updated_at->diffForHumans() }})</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                @if ($point->notes)
                <div class="info-card mb-4">
                    <div class="info-card-header">
                        <h5><i class="bi bi-sticky me-2"></i>Notes</h5>
                    </div>
                    <div class="info-card-body">
                        <div class="notes-content">
                            {{ $point->notes }}
                        </div>
                    </div>
                </div>
                @endif
            </div>


        </div>
    </div>
    <!-- Deactivate Confirmation Modal -->
    @if ($showDeactivateModal)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5)" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deactivation</h5>
                    <button type="button" class="btn-close"
                        wire:click="$set('showDeactivateModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-3">
                        <i class="bi bi-exclamation-triangle text-warning display-4"></i>
                        <h4 class="mt-3">Deactivate Point?</h4>
                        <p class="text-muted">
                            Are you sure you want to deactivate <strong>{{ $point->name }}</strong>?
                            This point will no longer be available for parcel operations.
                        </p>
                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-info-circle me-2"></i>
                            Active parcels will need to be redirected to other points.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        wire:click="$set('showDeactivateModal', false)">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-warning" wire:click="deactivatePoint">
                        <i class="bi bi-pause-circle me-2"></i>
                        Deactivate Point
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        /* Header Styling */
        .point-icon-large {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-active {
            background-color: rgba(40, 167, 69, 0.1);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        .status-inactive {
            background-color: rgba(108, 117, 125, 0.1);
            color: #495057;
            border: 1px solid rgba(108, 117, 125, 0.2);
        }

        .status-maintenance {
            background-color: rgba(255, 193, 7, 0.1);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        /* Quick Actions Bar */
        .quick-actions-bar {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 15px;
            height: 100%;
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-card:nth-child(1) .stat-icon {
            background: linear-gradient(135deg, #17a2b8, #138496);
        }

        .stat-card:nth-child(2) .stat-icon {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }

        .stat-card:nth-child(3) .stat-icon {
            background: linear-gradient(135deg, #6c757d, #495057);
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-light);
            margin: 5px 0;
        }

        .stat-trend {
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-trend.positive {
            color: #28a745;
        }

        /* Information Cards */
        .info-card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .info-card-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            background: #f8f9fa;
            border-radius: 12px 12px 0 0;
        }

        .info-card-header h5 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-dark);
            display: flex;
            align-items: center;
        }

        .info-card-body {
            padding: 20px;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            font-size: 1rem;
            color: var(--text-dark);
            font-weight: 500;
        }

        .info-value a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .info-value a:hover {
            text-decoration: underline;
        }

        /* Days Display */
        .days-display {
            display: flex;
            gap: 5px;
        }

        .day-badge {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.8rem;
            background-color: #f8f9fa;
            color: var(--text-light);
            border: 1px solid var(--border-color);
        }

        .day-badge.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Notes Content */
        .notes-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
            white-space: pre-line;
        }

        /* Activity Timeline */
        .activity-timeline {
            max-height: 300px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-text {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 3px;
        }

        .activity-time {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        /* Quick Links */
        .quick-links {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .quick-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            color: inherit;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .quick-link:hover {
            background-color: #f8f9fa;
            border-color: var(--primary-color);
            transform: translateX(5px);
        }

        .quick-link-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .quick-link-text {
            flex: 1;
        }

        .quick-link-text div {
            font-weight: 500;
            font-size: 0.95rem;
        }

        .quick-link-text small {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        /* Modal Styling */
        .qr-code-placeholder {
            display: inline-block;
            padding: 20px;
            background: white;
            border-radius: 10px;
            border: 2px dashed #dee2e6;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            background: white;
            border: 1px solid #dee2e6;
        }

        .qr-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 4px;
            padding: 10px;
        }

        .qr-cell {
            width: 100%;
            aspect-ratio: 1;
            background: #f8f9fa;
        }

        .qr-cell.active {
            background: #333;
        }

        /* Print Label Preview */
        .print-preview {
            border: 2px dashed #dee2e6;
            padding: 20px;
            background: white;
        }

        .label-template {
            max-width: 300px;
            margin: 0 auto;
        }

        .label-header {
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
            margin-bottom: 15px;
        }

        .label-header h4 {
            color: var(--primary-color);
            margin: 0;
        }

        .label-body {
            font-size: 0.9rem;
        }

        .label-row {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #dee2e6;
        }

        .label-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .label-footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 0.8rem;
            color: #666;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .header-actions {
                width: 100%;
                margin-top: 15px;
            }

            .stat-card {
                flex-direction: column;
                text-align: center;
            }

            .stat-content {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .point-icon-large {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .section-title {
                font-size: 1.3rem;
            }

            .quick-actions-bar .btn {
                flex: 1;
                min-width: 120px;
            }

            .info-card-header h5 {
                font-size: 0.95rem;
            }

            .info-card-body {
                padding: 15px;
            }
        }

        @media (max-width: 576px) {
            .quick-actions-bar .d-flex {
                flex-direction: column;
            }

            .quick-actions-bar .btn {
                width: 100%;
            }

            .days-display {
                justify-content: center;
            }

            .activity-item {
                flex-direction: column;
                text-align: center;
            }

            .activity-icon {
                margin: 0 auto;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Close modals on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    @if($showQrModal)
                    @this.set('showQrModal', false);
                    @endif
                    @if($showPrintModal)
                    @this.set('showPrintModal', false);
                    @endif
                    @if($showDeactivateModal)
                    @this.set('showDeactivateModal', false);
                    @endif
                }
            });

            // Print functionality
            window.addEventListener('beforeprint', function() {
                // Add any pre-print logic here
            });

            window.addEventListener('afterprint', function() {
                @if($showPrintModal)
                @this.set('showPrintModal', false);
                @endif
            });
        });

        // Livewire listeners
        Livewire.on('closeView', () => {
            // Close view and go back to list
            console.log('Closing view');
        });

        Livewire.on('editPoint', (pointId) => {
            // Navigate to edit page
            console.log('Edit point:', pointId);
        });

        Livewire.on('viewStation', (stationId) => {
            // View associated station
            console.log('View station:', stationId);
        });
    </script>
</div>
</div>