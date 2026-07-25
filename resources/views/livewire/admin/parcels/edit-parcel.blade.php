<div>
    <div>
        @section('title', 'Edit Parcel - ' . $parcel_number)

        <div class="content">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-edit text-primary"></i> Edit Parcel
                                <small class="text-muted">#{{ $parcel_number }}</small>
                            </h4>
                            <div>
                                <a href="{{ route('admin.parcels.view', $parcelId) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View Parcel
                                </a>
                                <a href="{{ route('admin.parcels.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Steps -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills nav-fill">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $currentStep == 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}"
                                            wire:click="$set('currentStep', 1)" style="cursor: pointer;">
                                            <i class="fas fa-user"></i>
                                            <span class="d-none d-md-inline">Step 1</span>
                                            <br class="d-md-none">
                                            <span class="d-none d-md-inline">Sender & Receiver</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $currentStep == 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}"
                                            wire:click="$set('currentStep', 2)" style="cursor: pointer;">
                                            <i class="fas fa-box"></i>
                                            <span class="d-none d-md-inline">Step 2</span>
                                            <br class="d-md-none">
                                            <span class="d-none d-md-inline">Parcel Details</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $currentStep == 3 ? 'active' : '' }}"
                                            wire:click="$set('currentStep', 3)" style="cursor: pointer;">
                                            <i class="fas fa-credit-card"></i>
                                            <span class="d-none d-md-inline">Step 3</span>
                                            <br class="d-md-none">
                                            <span class="d-none d-md-inline">Payment & Summary</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="updateParcel">
                    @csrf

                    <!-- Step 1: Sender & Receiver Information -->
                    <div class="row {{ $currentStep != 1 ? 'd-none' : '' }}">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-user"></i> Sender Information
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="sender_name">Sender Name <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('sender_name') is-invalid @enderror"
                                                    id="sender_name"
                                                    wire:model="sender_name"
                                                    placeholder="Enter sender's full name">
                                                @error('sender_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="sender_phone">Sender Phone <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('sender_phone') is-invalid @enderror"
                                                    id="sender_phone"
                                                    wire:model="sender_phone"
                                                    placeholder="Enter sender's phone number">
                                                @error('sender_phone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="sender_email">Sender Email</label>
                                                <input type="email"
                                                    class="form-control @error('sender_email') is-invalid @enderror"
                                                    id="sender_email"
                                                    wire:model="sender_email"
                                                    placeholder="Enter sender's email address">
                                                @error('sender_email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="sender_address">Sender Address</label>
                                                <input type="text"
                                                    class="form-control @error('sender_address') is-invalid @enderror"
                                                    id="sender_address"
                                                    wire:model="sender_address"
                                                    placeholder="Enter sender's address">
                                                @error('sender_address')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="sender_town_id">Sender Town <span class="text-danger">*</span></label>
                                                <select class="form-control @error('sender_town_id') is-invalid @enderror"
                                                    id="sender_town_id"
                                                    wire:model.live="sender_town_id">
                                                    <option value="">Select Town</option>
                                                    @foreach($towns as $town)
                                                    <option value="{{ $town->id }}">{{ $town->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('sender_town_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ $sender_pick_up_drop_off_point_id  }}
                                                <label for="sender_pick_up_drop_off_point_id">Pickup Point <span class="text-danger">*</span></label>
                                                <select class="form-control @error('sender_pick_up_drop_off_point_id') is-invalid @enderror"
                                                    id="sender_pick_up_drop_off_point_id"
                                                    wire:model="sender_pick_up_drop_off_point_id">
                                                    <option value="">Select Pickup Point</option>
                                                    @foreach($senderPickUpAndDropOffPoints as $point)
                                                    <option value="{{ $point->id }}">{{ $point->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('sender_pick_up_drop_off_point_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="sender_notes">Sender Notes</label>
                                                <textarea class="form-control @error('sender_notes') is-invalid @enderror"
                                                    id="sender_notes"
                                                    wire:model="sender_notes"
                                                    rows="2"
                                                    placeholder="Any additional notes about the sender"></textarea>
                                                @error('sender_notes')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-user-tie"></i> Receiver Information
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="receiver_name">Receiver Name <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('receiver_name') is-invalid @enderror"
                                                    id="receiver_name"
                                                    wire:model="receiver_name"
                                                    placeholder="Enter receiver's full name">
                                                @error('receiver_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="receiver_phone">Receiver Phone <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('receiver_phone') is-invalid @enderror"
                                                    id="receiver_phone"
                                                    wire:model="receiver_phone"
                                                    placeholder="Enter receiver's phone number">
                                                @error('receiver_phone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="receiver_email">Receiver Email</label>
                                                <input type="email"
                                                    class="form-control @error('receiver_email') is-invalid @enderror"
                                                    id="receiver_email"
                                                    wire:model="receiver_email"
                                                    placeholder="Enter receiver's email address">
                                                @error('receiver_email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="receiver_address">Receiver Address</label>
                                                <input type="text"
                                                    class="form-control @error('receiver_address') is-invalid @enderror"
                                                    id="receiver_address"
                                                    wire:model="receiver_address"
                                                    placeholder="Enter receiver's address">
                                                @error('receiver_address')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="receiver_county_id">Receiver County <span class="text-danger">*</span></label>
                                                <select class="form-control @error('receiver_county_id') is-invalid @enderror"
                                                    id="receiver_county_id"
                                                    wire:model.live="receiver_county_id">
                                                    <option value="">Select County</option>
                                                    @foreach($counties as $county)
                                                    <option value="{{ $county->id }}">{{ $county->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('receiver_county_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="receiver_town_id">Receiver Town <span class="text-danger">*</span></label>
                                                <select class="form-control @error('receiver_town_id') is-invalid @enderror"
                                                    id="receiver_town_id"
                                                    wire:model.live="receiver_town_id">
                                                    <option value="">Select Town</option>
                                                    @foreach($countyTowns as $town)
                                                    <option value="{{ $town->id }}">{{ $town->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('receiver_town_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="delivery_pick_up_drop_off_point_id">Delivery Point <span class="text-danger">*</span></label>
                                                <select class="form-control @error('delivery_pick_up_drop_off_point_id') is-invalid @enderror"
                                                    id="delivery_pick_up_drop_off_point_id"
                                                    wire:model="delivery_pick_up_drop_off_point_id">
                                                    <option value="">Select Delivery Point</option>
                                                    @foreach($receiverPickUpAndDropOffPoints as $point)
                                                    <option value="{{ $point->id }}">{{ $point->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('delivery_pick_up_drop_off_point_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="receiver_notes">Receiver Notes</label>
                                                <textarea class="form-control @error('receiver_notes') is-invalid @enderror"
                                                    id="receiver_notes"
                                                    wire:model="receiver_notes"
                                                    rows="2"
                                                    placeholder="Any additional notes about the receiver"></textarea>
                                                @error('receiver_notes')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary float-right" wire:click="nextStep">
                                    <i class="fas fa-arrow-right"></i> Next Step
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Parcel Details -->
                    <div class="row {{ $currentStep != 2 ? 'd-none' : '' }}">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-box"></i> Parcel Details
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="parcel_type">Parcel Type <span class="text-danger">*</span></label>
                                                <select class="form-control @error('parcel_type') is-invalid @enderror"
                                                    id="parcel_type"
                                                    wire:model="parcel_type">
                                                    <option value="">Select Parcel Type</option>
                                                    @foreach($parcelTypes as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                @error('parcel_type')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="package_type">Package Type <span class="text-danger">*</span></label>
                                                <select class="form-control @error('package_type') is-invalid @enderror"
                                                    id="package_type"
                                                    wire:model="package_type">
                                                    <option value="">Select Package Type</option>
                                                    @foreach($packageTypes as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                @error('package_type')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="weight">Weight (kg) <span class="text-danger">*</span></label>
                                                <input type="number"
                                                    step="0.01"
                                                    class="form-control @error('weight') is-invalid @enderror"
                                                    id="weight"
                                                    wire:model="weight"
                                                    placeholder="Enter weight in kg">
                                                @error('weight')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="declared_value">Declared Value (KES)</label>
                                                <input type="number"
                                                    step="0.01"
                                                    class="form-control @error('declared_value') is-invalid @enderror"
                                                    id="declared_value"
                                                    wire:model="declared_value"
                                                    placeholder="Enter declared value">
                                                @error('declared_value')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="insurance_required">
                                                    <input type="checkbox"
                                                        class="@error('insurance_required') is-invalid @enderror"
                                                        id="insurance_required"
                                                        wire:model="insurance_required">
                                                    Require Insurance (2% of declared value)
                                                </label>
                                                @if($insurance_required && $insurance_charge > 0)
                                                <small class="text-muted d-block">
                                                    Insurance Charge: KES {{ number_format($insurance_charge, 2) }}
                                                </small>
                                                @endif
                                                @error('insurance_required')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="content_description">Content Description <span class="text-danger">*</span></label>
                                                <textarea class="form-control @error('content_description') is-invalid @enderror"
                                                    id="content_description"
                                                    wire:model="content_description"
                                                    rows="3"
                                                    placeholder="Describe the contents of the parcel"></textarea>
                                                @error('content_description')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="special_instructions">Special Instructions</label>
                                                <textarea class="form-control @error('special_instructions') is-invalid @enderror"
                                                    id="special_instructions"
                                                    wire:model="special_instructions"
                                                    rows="2"
                                                    placeholder="Any special instructions for handling this parcel"></textarea>
                                                @error('special_instructions')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card-footer">
                                <button type="button" class="btn btn-secondary" wire:click="previousStep">
                                    <i class="fas fa-arrow-left"></i> Previous Step
                                </button>
                                <button type="button" class="btn btn-primary float-right" wire:click="nextStep">
                                    <i class="fas fa-arrow-right"></i> Next Step
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Payment & Summary -->
                    <div class="row {{ $currentStep != 3 ? 'd-none' : '' }}">
                        <div class="col-md-8">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-credit-card"></i> Payment Information
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                                <select class="form-control @error('payment_method') is-invalid @enderror"
                                                    id="payment_method"
                                                    wire:model="payment_method">
                                                    @foreach($paymentMethods as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                @error('payment_method')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="payment_status">Payment Status <span class="text-danger">*</span></label>
                                                <select class="form-control @error('payment_status') is-invalid @enderror"
                                                    id="payment_status"
                                                    wire:model="payment_status">
                                                    @foreach($paymentStatuses as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                @error('payment_status')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="booking_type">Booking Type</label>
                                                <select class="form-control @error('booking_type') is-invalid @enderror"
                                                    id="booking_type"
                                                    wire:model="booking_type">
                                                    @foreach($bookingTypes as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                @error('booking_type')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-receipt"></i> Price Breakdown
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Base Price</strong></td>
                                                    <td class="text-right">KES {{ number_format($base_price, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Weight Charge</strong></td>
                                                    <td class="text-right">KES {{ number_format($weight_charge, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Distance Charge</strong></td>
                                                    <td class="text-right">KES {{ number_format($distance_charge, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Special Handling Charge</strong></td>
                                                    <td class="text-right">KES {{ number_format($special_handling_charge, 2) }}</td>
                                                </tr>
                                                @if($insurance_required && $insurance_charge > 0)
                                                <tr>
                                                    <td><strong>Insurance Charge</strong></td>
                                                    <td class="text-right">KES {{ number_format($insurance_charge, 2) }}</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td><strong>Tax (16% VAT)</strong></td>
                                                    <td class="text-right">KES {{ number_format($tax_amount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Discount</strong></td>
                                                    <td class="text-right">KES {{ number_format($discount_amount, 2) }}</td>
                                                </tr>
                                                <tr class="bg-light">
                                                    <td><strong class="text-primary">Total Amount</strong></td>
                                                    <td class="text-right">
                                                        <strong class="text-primary">KES {{ number_format($total_amount, 2) }}</strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-check-circle"></i> Summary
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Parcel Number:</strong>
                                        <span class="float-right">{{ $parcel_number }}</span>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <strong>Sender:</strong>
                                        <span class="float-right">{{ $sender_name }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Receiver:</strong>
                                        <span class="float-right">{{ $receiver_name }}</span>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <strong>Parcel Type:</strong>
                                        <span class="float-right">{{ ucfirst($parcel_type) }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Weight:</strong>
                                        <span class="float-right">{{ $weight }} kg</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Package Type:</strong>
                                        <span class="float-right">{{ ucfirst($package_type) }}</span>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <strong>Payment Status:</strong>
                                        <span class="float-right">
                                            @if($payment_status == 'paid')
                                            <span class="badge badge-success">Paid</span>
                                            @elseif($payment_status == 'partially_paid')
                                            <span class="badge badge-warning">Partially Paid</span>
                                            @else
                                            <span class="badge badge-secondary">Pending</span>
                                            @endif
                                        </span>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <strong>Total Amount:</strong>
                                        <span class="float-right text-primary font-weight-bold">
                                            KES {{ number_format($total_amount, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card-footer">
                                <button type="button" class="btn btn-secondary" wire:click="previousStep">
                                    <i class="fas fa-arrow-left"></i> Previous Step
                                </button>
                                <button type="submit" class="btn btn-success float-right" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="fas fa-save"></i> Update Parcel
                                    </span>
                                    <span wire:loading>
                                        <i class="fas fa-spinner fa-spin"></i> Updating...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .nav-pills .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }

        .nav-pills .nav-link.completed {
            background-color: #28a745;
            color: #fff;
        }

        .nav-pills .nav-link {
            border-radius: 0;
            padding: 12px 16px;
        }

        .nav-pills .nav-link i {
            margin-right: 8px;
        }

        .card-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .table td {
            vertical-align: middle;
        }
    </style>
    @endpush
</div>