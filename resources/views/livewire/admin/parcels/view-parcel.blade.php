<div>
    <div class="card card-default">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-box mr-2"></i>
                    Parcel Details: {{ $parcel->parcel_id }}
                </h3>
                <div class="card-tools">
                    @php
                        $statusBadge = $this->getStatusBadge($parcel->current_status);
                    @endphp
                    <span class="badge badge-{{ $statusBadge['color'] }} mr-2">
                        <i class="fas {{ $statusBadge['icon'] }} mr-1"></i>
                        {{ ucfirst(str_replace('_', ' ', $parcel->current_status)) }}
                    </span>
                    
                    <span class="badge badge-{{ $this->getPaymentStatusColor($parcel->payment_status) }}">
                        <i class="fas fa-money-bill-wave mr-1"></i>
                        {{ ucfirst($parcel->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $parcel->weight ?? 'N/A' }} {{ $parcel->weight_unit }}</h3>
                            <p>Weight</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-weight"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $parcel->parcel_dimensions ?? 'N/A' }}</h3>
                            <p>Dimensions</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-ruler-combined"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>KES {{ number_format($parcel->total_amount, 2) }}</h3>
                            <p>Total Amount</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $parcel->created_at->format('d/m/Y') }}</h3>
                            <p>Created Date</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="btn-group w-100" role="group">
                        <button class="btn btn-primary" wire:click="$set('activeTab', 'details')">
                            <i class="fas fa-info-circle mr-1"></i> Details
                        </button>
                        <button class="btn btn-info" wire:click="$set('activeTab', 'tracking')">
                            <i class="fas fa-history mr-1"></i> Tracking
                        </button>
                        <button class="btn btn-success" wire:click="$set('activeTab', 'payment')">
                            <i class="fas fa-credit-card mr-1"></i> Payment
                        </button>
                        <button class="btn btn-warning" wire:click="$set('activeTab', 'assignment')">
                            <i class="fas fa-user-tag mr-1"></i> Assignment
                        </button>
                        <button class="btn btn-danger" wire:click="$set('activeTab', 'pickup')">
                            <i class="fas fa-box-open mr-1"></i> Pickup
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="row">
                <div class="col-md-8">
                    <!-- Details Tab -->
                    @if($activeTab === 'details')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-2"></i>Parcel Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-primary">Sender Information</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th style="width: 40%">Name:</th>
                                            <td>{{ $parcel->sender_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $parcel->sender_phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $parcel->sender_email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Town:</th>
                                            <td>{{ $parcel->senderTown->name ?? 'N/A' }}</td>
                                        </tr>
                                        @if($parcel->sender_notes)
                                        <tr>
                                            <th>Notes:</th>
                                            <td>{{ $parcel->sender_notes }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5 class="text-success">Receiver Information</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th style="width: 40%">Name:</th>
                                            <td>{{ $parcel->receiver_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $parcel->receiver_phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $parcel->receiver_email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address:</th>
                                            <td>{{ $parcel->receiver_address }}</td>
                                        </tr>
                                        <tr>
                                            <th>Town:</th>
                                            <td>{{ $parcel->receiverTown->name ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="text-warning">Parcel Details</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th style="width: 20%">Type:</th>
                                            <td>{{ ucfirst($parcel->parcel_type) }}</td>
                                            <th style="width: 20%">Package Type:</th>
                                            <td>
                                                <span class="badge badge-warning">
                                                    {{ ucfirst($parcel->package_type) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Weight:</th>
                                            <td>{{ $parcel->weight }} {{ $parcel->weight_unit }}</td>
                                            <th>Dimensions:</th>
                                            <td>{{ $parcel->parcel_dimensions }}</td>
                                        </tr>
                                        <tr>
                                            <th>Volume:</th>
                                            <td>{{ $parcel->parcel_volume }} cm³</td>
                                            <th>Size Category:</th>
                                            <td>{{ ucfirst($parcel->parcel_size_category) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Declared Value:</th>
                                            <td>KES {{ number_format($parcel->declared_value, 2) }}</td>
                                            <th>Insurance Required:</th>
                                            <td>
                                                @if($parcel->insurance_required)
                                                    <span class="badge badge-success">Yes</span>
                                                @else
                                                    <span class="badge badge-secondary">No</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Content Description:</th>
                                            <td colspan="3">{{ $parcel->content_description ?? 'N/A' }}</td>
                                        </tr>
                                        @if($parcel->special_instructions)
                                        <tr>
                                            <th>Special Instructions:</th>
                                            <td colspan="3" class="text-danger">{{ $parcel->special_instructions }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Tracking Tab -->
                    @if($activeTab === 'tracking')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history mr-2"></i>Tracking History
                            </h3>
                            <div class="card-tools">
                                <button class="btn btn-sm btn-primary" wire:click="$set('showUpdateStatusModal', true)">
                                    <i class="fas fa-plus mr-1"></i> Update Status
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($parcel->statuses && $parcel->statuses->count() > 0)
                                <div class="timeline">
                                    @foreach($parcel->statuses as $status)
                                    <div>
                                        <i class="fas fa-circle bg-{{ $this->getStatusBadge($status->status)['color'] }}"></i>
                                        <div class="timeline-item">
                                            <span class="time">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $status->created_at->format('d/m/Y H:i') }}
                                            </span>
                                            <h3 class="timeline-header">
                                                <span class="badge badge-{{ $this->getStatusBadge($status->status)['color'] }}">
                                                    {{ ucfirst(str_replace('_', ' ', $status->status)) }}
                                                </span>
                                                @if($status->changed_by_type === 'driver' && $status->driver)
                                                    <small class="ml-2">by {{ $status->driver->full_name }} (Driver)</small>
                                                @elseif($status->changer)
                                                    <small class="ml-2">by {{ $status->changer->name }}</small>
                                                @elseif($status->changed_by_type === 'system')
                                                    <small class="ml-2">by System</small>
                                                @endif
                                            </h3>
                                            @if($status->notes)
                                            <div class="timeline-body">
                                                {{ $status->notes }}
                                            </div>
                                            @endif
                                            @if($status->otp)
                                            <div class="timeline-footer">
                                                <span class="badge badge-info">
                                                    OTP: {{ $status->otp }} 
                                                    @if($status->otp_verified)
                                                        <i class="fas fa-check-circle text-success ml-1"></i>
                                                    @endif
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <p>No tracking history available</p>
                                    <button class="btn btn-sm btn-primary" wire:click="$set('showUpdateStatusModal', true)">
                                        <i class="fas fa-plus mr-1"></i> Add First Status
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Payment Tab -->
                    @if($activeTab === 'payment')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-credit-card mr-2"></i>Payment Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Pricing Breakdown</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Base Price:</th>
                                            <td>KES {{ number_format($parcel->base_price, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Weight Charge:</th>
                                            <td>KES {{ number_format($parcel->weight_charge, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Distance Charge:</th>
                                            <td>KES {{ number_format($parcel->distance_charge, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Special Handling:</th>
                                            <td>KES {{ number_format($parcel->special_handling_charge, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Insurance Charge:</th>
                                            <td>KES {{ number_format($parcel->insurance_charge, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tax:</th>
                                            <td>KES {{ number_format($parcel->tax_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Discount:</th>
                                            <td>KES {{ number_format($parcel->discount_amount, 2) }}</td>
                                        </tr>
                                        <tr class="font-weight-bold">
                                            <th>Total:</th>
                                            <td class="text-success">KES {{ number_format($parcel->total_amount, 2) }}</td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5>Payment Details</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Payment Method:</th>
                                            <td>{{ ucfirst($parcel->payment_method ?? 'N/A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Status:</th>
                                            <td>
                                                <span class="badge badge-{{ $this->getPaymentStatusColor($parcel->payment_status) }}">
                                                    {{ ucfirst($parcel->payment_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Paid At:</th>
                                            <td>{{ $parcel->paid_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Payout:</th>
                                            <td>KES {{ number_format($parcel->payout, 2) }}</td>
                                        </tr>
                                    </table>

                                    @if($parcel->payments && $parcel->payments->count() > 0)
                                        <h5 class="mt-3">Payment Transactions</h5>
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Reference</th>
                                                    <th>Amount</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($parcel->payments as $payment)
                                                <tr>
                                                    <td>{{ $payment->reference_number }}</td>
                                                    <td>KES {{ number_format($payment->amount, 2) }}</td>
                                                    <td>{{ $payment->payment_date?->format('d/m/Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Assignment Tab -->
                    @if($activeTab === 'assignment')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-tag mr-2"></i>Assignment Information
                            </h3>
                            <div class="card-tools">
                                <button class="btn btn-sm btn-primary" wire:click="assignToDriver">
                                    <i class="fas fa-user-plus mr-1"></i> Assign Driver
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Pickup Details</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Pickup Partner:</th>
                                            <td>{{ $parcel->senderPartner->company_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pickup Point:</th>
                                            <td>{{ $parcel->senderPickUpDropOffPoint->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>PHA:</th>
                                            <td>{{ $parcel->pha->full_name ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5>Delivery Details</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Transport Partner:</th>
                                            <td>{{ $parcel->transportPartner->company_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Delivery Point:</th>
                                            <td>{{ $parcel->deliveryStation->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Driver:</th>
                                            <td>{{ $parcel->driver->full_name ?? 'Not Assigned' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($parcel->driver)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5>Driver Information</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th style="width: 20%">Name:</th>
                                            <td>{{ $parcel->driver->full_name }}</td>
                                            <th style="width: 20%">Phone:</th>
                                            <td>{{ $parcel->driver->phone_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>License:</th>
                                            <td>{{ $parcel->driver->driving_license_number }}</td>
                                            <th>Status:</th>
                                            <td>
                                                <span class="badge badge-{{ $parcel->driver->status === 'active' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($parcel->driver->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Pickup Tab -->
                    @if($activeTab === 'pickup')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-box-open mr-2"></i>Pickup Information
                            </h3>
                            @if(!$parcel->parcelPickUp)
                            <div class="card-tools">
                                <button class="btn btn-sm btn-success" wire:click="$set('showPickupModal', true)">
                                    <i class="fas fa-plus mr-1"></i> Record Pickup
                                </button>
                            </div>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($parcel->parcelPickUp)
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Pickup Person Details</h5>
                                        <table class="table table-sm">
                                            <tr>
                                                <th>Type:</th>
                                                <td>
                                                    <span class="badge badge-{{ $parcel->parcelPickUp->isOwnerPickup() ? 'success' : 'info' }}">
                                                        {{ ucfirst($parcel->parcelPickUp->pickup_person_type) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Name:</th>
                                                <td>{{ $parcel->parcelPickUp->pickup_person_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone:</th>
                                                <td>{{ $parcel->parcelPickUp->pickup_person_phone }}</td>
                                            </tr>
                                            @if($parcel->parcelPickUp->pickup_person_id_number)
                                            <tr>
                                                <th>ID Number:</th>
                                                <td>{{ $parcel->parcelPickUp->pickup_person_id_number }}</td>
                                            </tr>
                                            @endif
                                            @if($parcel->parcelPickUp->pickup_person_relationship)
                                            <tr>
                                                <th>Relationship:</th>
                                                <td>{{ $parcel->parcelPickUp->pickup_person_relationship }}</td>
                                            </tr>
                                            @endif
                                            @if($parcel->parcelPickUp->pickup_reason)
                                            <tr>
                                                <th>Reason:</th>
                                                <td>{{ $parcel->parcelPickUp->pickup_reason }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <h5>Verification Details</h5>
                                        <table class="table table-sm">
                                            <tr>
                                                <th>Verified By:</th>
                                                <td>{{ $parcel->parcelPickUp->verifier->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Verified At:</th>
                                                <td>{{ $parcel->parcelPickUp->verified_at?->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            @if($parcel->parcelPickUp->notes)
                                            <tr>
                                                <th>Notes:</th>
                                                <td>{{ $parcel->parcelPickUp->notes }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <p>No pickup recorded yet</p>
                                    <button class="btn btn-success" wire:click="$set('showPickupModal', true)">
                                        <i class="fas fa-plus mr-1"></i> Record Pickup
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column - Quick Actions -->
                <div class="col-md-4">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bolt mr-2"></i>Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-block" wire:click="$set('showUpdateStatusModal', true)">
                                    <i class="fas fa-sync-alt mr-2"></i> Update Status
                                </button>
                                
                                <button class="btn btn-info btn-block" wire:click="generateOtp">
                                    <i class="fas fa-key mr-2"></i> Generate OTP
                                </button>
                                
                                <button class="btn btn-success btn-block" wire:click="printLabel">
                                    <i class="fas fa-print mr-2"></i> Print Label
                                </button>
                                
                                <a href="{{ route('admin.parcels.edit', $parcel->id) }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-edit mr-2"></i> Edit Parcel
                                </a>
                                
                                <button class="btn btn-danger btn-block" wire:click="confirmDelete">
                                    <i class="fas fa-trash mr-2"></i> Delete Parcel
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clock mr-2"></i>Timeline
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Created
                                    <span class="text-muted">{{ $parcel->created_at->format('d/m/Y H:i') }}</span>
                                </li>
                                @if($parcel->paid_at)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Paid
                                    <span class="text-muted">{{ $parcel->paid_at->format('d/m/Y H:i') }}</span>
                                </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Last Updated
                                    <span class="text-muted">{{ $parcel->updated_at->format('d/m/Y H:i') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('admin.parcels.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Parcels
                    </a>
                    <a href="{{ route('admin.parcels.create') }}" class="btn btn-success">
                        <i class="fas fa-plus mr-2"></i>Add New Parcel
                    </a>
                </div>
                <div class="col-md-6 text-right">
                    <span class="text-muted">
                        Created by: {{ $parcel->createdBy->name ?? 'System' }} | 
                        Last updated: {{ $parcel->updated_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    @if($showUpdateStatusModal)
    <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
         style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Update Parcel Status
                    </h5>
                    <button type="button" class="close" wire:click="$set('showUpdateStatusModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="updateStatus">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="newStatus">New Status <span class="text-danger">*</span></label>
                            <select wire:model="newStatus" id="newStatus" class="form-control" required>
                                <option value="">Select Status</option>
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('newStatus') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="statusNotes">Notes</label>
                            <textarea wire:model="statusNotes" id="statusNotes" 
                                      class="form-control" rows="3"
                                      placeholder="Add any notes about this status update..."></textarea>
                            @error('statusNotes') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" 
                                wire:click="$set('showUpdateStatusModal', false)">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Record Pickup Modal -->
    @if($showPickupModal)
    <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
         style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-box-open mr-2"></i>
                        Record Parcel Pickup
                    </h5>
                    <button type="button" class="close" wire:click="$set('showPickupModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="recordPickup">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pickup Person Type</label>
                            <div class="form-check">
                                <input type="radio" wire:model="pickupPersonType" value="owner" class="form-check-input" id="owner">
                                <label class="form-check-label" for="owner">Owner</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" wire:model="pickupPersonType" value="other" class="form-check-input" id="other">
                                <label class="form-check-label" for="other">Other Person</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Pickup Person Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model="pickupPersonName" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Pickup Person Phone <span class="text-danger">*</span></label>
                            <input type="text" wire:model="pickupPersonPhone" class="form-control" required>
                        </div>

                        @if($pickupPersonType === 'other')
                            <div class="form-group">
                                <label>ID Number <span class="text-danger">*</span></label>
                                <input type="text" wire:model="pickupPersonIdNumber" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Relationship to Owner <span class="text-danger">*</span></label>
                                <select wire:model="pickupPersonRelationship" class="form-control" required>
                                    <option value="">Select Relationship</option>
                                    <option value="Spouse">Spouse</option>
                                    <option value="Parent">Parent</option>
                                    <option value="Child">Child</option>
                                    <option value="Sibling">Sibling</option>
                                    <option value="Friend">Friend</option>
                                    <option value="Colleague">Colleague</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Reason for Pickup</label>
                                <textarea wire:model="pickupReason" class="form-control" rows="2"></textarea>
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Additional Notes</label>
                            <textarea wire:model="pickupNotes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" 
                                wire:click="$set('showPickupModal', false)">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Record Pickup
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- OTP Modal -->
    @if($showOtpModal)
    <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
         style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-key mr-2"></i>
                        OTP Generated Successfully
                    </h5>
                    <button type="button" class="close text-white" wire:click="$set('showOtpModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <h1 class="display-4 font-weight-bold text-success">{{ $otp }}</h1>
                    <p class="text-muted mt-3">
                        Share this OTP with the driver for delivery verification.
                        <br>
                        <small>The OTP will expire in 12 hours.</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i> Print OTP
                    </button>
                    <button type="button" class="btn btn-secondary" 
                            wire:click="$set('showOtpModal', false)">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .timeline {
            position: relative;
            margin: 0 0 30px 0;
            padding: 0;
            list-style: none;
        }
        
        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #ddd;
            left: 31px;
            margin: 0;
            border-radius: 2px;
        }
        
        .timeline > div {
            position: relative;
            margin-right: 10px;
            margin-bottom: 15px;
        }
        
        .timeline > div:before,
        .timeline > div:after {
            content: " ";
            display: table;
        }
        
        .timeline > div:after {
            clear: both;
        }
        
        .timeline > div > .timeline-item {
            margin-top: 0;
            background: #fff;
            color: #444;
            margin-left: 60px;
            margin-right: 15px;
            padding: 0;
            position: relative;
            border-radius: 3px;
        }
        
        .timeline > div > .timeline-item > .time {
            color: #999;
            float: right;
            padding: 10px;
            font-size: 12px;
        }
        
        .timeline > div > .timeline-item > .timeline-header {
            margin: 0;
            color: #555;
            border-bottom: 1px solid #f4f4f4;
            padding: 10px;
            font-size: 16px;
            line-height: 1.1;
        }
        
        .timeline > div > .timeline-item > .timeline-body {
            padding: 10px;
        }
        
        .timeline > div > .timeline-item > .timeline-footer {
            padding: 10px;
        }
        
        .timeline > div > .fas,
        .timeline > div > .fas {
            width: 30px;
            height: 30px;
            font-size: 15px;
            line-height: 30px;
            position: absolute;
            color: #fff;
            background: #adb5bd;
            border-radius: 50%;
            text-align: center;
            left: 18px;
            top: 0;
        }
        
        .small-box {
            border-radius: 0.25rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            display: block;
            margin-bottom: 20px;
            position: relative;
            color: #fff;
        }
        
        .small-box > .inner {
            padding: 10px;
        }
        
        .small-box > .small-box-footer {
            position: relative;
            text-align: center;
            padding: 3px 0;
            color: rgba(255,255,255,.8);
            display: block;
            z-index: 10;
            background: rgba(0,0,0,.1);
            text-decoration: none;
        }
        
        .small-box > .small-box-footer:hover {
            color: #fff;
            background: rgba(0,0,0,.15);
        }
        
        .small-box h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 10px 0;
            white-space: nowrap;
            padding: 0;
        }
        
        .small-box p {
            font-size: 1rem;
        }
        
        .small-box .icon {
            transition: all .3s linear;
            position: absolute;
            top: -10px;
            right: 10px;
            z-index: 0;
            font-size: 2.5rem;
            color: rgba(0,0,0,.15);
        }
        
        .bg-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }
        
        .bg-success {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        }
        
        .bg-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        }
        
        .bg-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        
        .d-grid {
            display: grid;
        }
        
        .gap-2 {
            gap: 0.5rem;
        }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('print-label', (data) => {
                // Implement label printing logic
                window.open('/admin/parcels/' + data.parcel_id + '/label', '_blank');
            });

            Livewire.on('notify', (data) => {
                // You can integrate with a notification library like Toastr or SweetAlert
                if (data.type === 'success') {
                    alert('Success: ' + data.message);
                } else if (data.type === 'error') {
                    alert('Error: ' + data.message);
                }
            });
        });
    </script>
    @endpush
</div>