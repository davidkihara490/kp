<div>
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 bg-gradient-primary text-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="display-6 fw-bold mb-2">Create New Parcel</h2>
                                <p class="mb-0 opacity-75">Add a new parcel for delivery with ease</p>
                            </div>
                            <a href="{{ route('partners.parcels.index') }}"
                                class="btn btn-light btn-lg rounded-pill px-4">
                                <i class="bi bi-arrow-left me-2"></i>
                                Back to Parcels
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="progress-steps">
                    <div class="step-item {{ $currentStep >= 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}">
                        <div class="step-icon">
                            <i class="bi bi-person-up"></i>
                        </div>
                        <div class="step-label">Sender & Receiver</div>
                    </div>
                    <div class="step-connector {{ $currentStep > 1 ? 'active' : '' }}"></div>

                    <div class="step-item {{ $currentStep >= 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}">
                        <div class="step-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="step-label">Parcel Details</div>
                    </div>
                    <div class="step-connector {{ $currentStep > 2 ? 'active' : '' }}"></div>

                    <div class="step-item {{ $currentStep >= 3 ? 'active' : '' }}">
                        <div class="step-icon">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div class="step-label">Review & Submit</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 1: Sender & Receiver -->
        @if($currentStep == 1)
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="bi bi-person-lines-fill text-primary me-2"></i>
                            Sender & Receiver Information
                        </h5>
                    </div>

                    <div class="card-body p-4">


                        <div class="row g-4">
                            <!-- Sender Information -->
                            <div class="col-lg-6">
                                <div class="info-section p-4 bg-light rounded-3">
                                    <h6 class="section-title fw-semibold mb-4">
                                        <i class="bi bi-person-up text-primary me-2"></i>
                                        Sender Information
                                    </h6>

                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label required">Full Name</label>
                                            <input type="text"
                                                class="form-control @error('sender_name') is-invalid @enderror"
                                                wire:model="sender_name"
                                                placeholder="Enter sender's full name">
                                            @error('sender_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label required">Phone Number</label>
                                            <input type="text"
                                                class="form-control @error('sender_phone') is-invalid @enderror"
                                                wire:model="sender_phone"
                                                placeholder="+254 XXX XXX XXX"
                                                x-data
                                                x-init="
                                                       $el.addEventListener('input', (e) => {
                                                           let value = e.target.value.replace(/\D/g, '');
                                                           if (value.startsWith('254')) value = '+' + value;
                                                           else if (value.startsWith('0')) value = '+254' + value.substring(1);
                                                           else if (value.length > 0 && !value.startsWith('+')) value = '+254' + value;
                                                           e.target.value = value;
                                                       })
                                                   ">
                                            @error('sender_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Email Address</label>
                                            <input type="email"
                                                class="form-control @error('sender_email') is-invalid @enderror"
                                                wire:model="sender_email"
                                                placeholder="email@example.com">
                                            @error('sender_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label required">Town</label>
                                            <select class="form-select @error('sender_town_id') is-invalid @enderror"
                                                wire:model.live.debounce.750ms="sender_town_id">
                                                <option value="">Select town</option>
                                                @foreach($towns as $town)
                                                <option value="{{ $town->id }}">{{ $town->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('sender_town_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label required">Pickup Point</label>
                                            <select class="form-select @error('sender_pick_up_drop_off_point_id') is-invalid @enderror"
                                                wire:model="sender_pick_up_drop_off_point_id">
                                                <option value="">Select pickup point</option>
                                                @foreach($pickUpAndDropOffPoints as $point)
                                                <option value="{{ $point->id }}">{{ $point->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('sender_pick_up_drop_off_point_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Receiver Information -->
                            <div class="col-lg-6">
                                <div class="info-section p-4 bg-light rounded-3">
                                    <h6 class="section-title fw-semibold mb-4">
                                        <i class="bi bi-person-down text-success me-2"></i>
                                        Receiver Information
                                    </h6>

                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label required">Full Name</label>
                                            <input type="text"
                                                class="form-control @error('receiver_name') is-invalid @enderror"
                                                wire:model="receiver_name"
                                                placeholder="Enter receiver's full name">
                                            @error('receiver_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label required">Phone Number</label>
                                            <input type="text"
                                                class="form-control @error('receiver_phone') is-invalid @enderror"
                                                wire:model="receiver_phone"
                                                placeholder="+254 XXX XXX XXX">
                                            @error('receiver_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Email Address</label>
                                            <input type="email"
                                                class="form-control @error('receiver_email') is-invalid @enderror"
                                                wire:model="receiver_email"
                                                placeholder="email@example.com">
                                            @error('receiver_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>


                                        <div class="col-md-6">
                                            <label class="form-label required">Town</label>
                                            <select class="form-select @error('receiver_town_id') is-invalid @enderror"
                                                wire:model.live.debounce.750ms="receiver_town_id">
                                                <option value="">Select town</option>
                                                @foreach($towns as $town)
                                                <option value="{{ $town->id }}">{{ $town->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('receiver_town_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label required">Delivery Point</label>
                                            <select class="form-select @error('delivery_pick_up_drop_off_point_id') is-invalid @enderror"
                                                wire:model="delivery_pick_up_drop_off_point_id">
                                                <option value="">Select delivery point</option>
                                                @foreach($pickUpAndDropOffPoints as $point)
                                                <option value="{{ $point->id }}">{{ $point->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('delivery_pick_up_drop_off_point_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Step 2: Parcel Details -->
        @if($currentStep == 2)
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="bi bi-box-seam text-primary me-2"></i>
                            Parcel Details & Pricing
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Parcel Information - Left Column -->
                            <div class="col-lg-7">
                                <div class="pe-lg-4">
                                    <div class="section-divider mb-4">
                                        <h6 class="section-title">
                                            <i class="bi bi-info-circle text-primary me-2"></i>
                                            Parcel Information
                                        </h6>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label required">Parcel Type</label>
                                            <select class="form-select form-select-lg @error('parcel_type') is-invalid @enderror" wire:model.live.debounce.750ms="parcel_type">
                                                <option value="">Select parcel type</option>
                                                @foreach($parcelTypes as $value => $label)
                                                <option value="{{ $label->id }}">{{ $label->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('parcel_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label required">Package Type</label>
                                            <select class="form-select form-select-lg @error('package_type') is-invalid @enderror" wire:model="package_type">
                                                <option value="">Select package type</option>
                                                @foreach($packageTypes as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('package_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label required">Weight</label>
                                            <div class="input-group input-group-lg">
                                                <input type="number" class="form-control @error('weight') is-invalid @enderror"
                                                    wire:model.live.debounce.750ms="weight" step="0.01" min="0.1" placeholder="0.00">
                                                <select class="form-select" wire:model="weight_unit" style="max-width: 100px;">
                                                    <option value="kg">kg</option>
                                                    <option value="g">g</option>
                                                    <option value="lb">lb</option>
                                                </select>
                                            </div>
                                            @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Declared Value (KES)</label>
                                            <input type="number" class="form-control form-control-lg @error('declared_value') is-invalid @enderror"
                                                wire:model="declared_value" step="0.01" min="0" placeholder="0.00">
                                            @error('declared_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label required">Content Description</label>
                                            <textarea class="form-control form-control-lg @error('content_description') is-invalid @enderror"
                                                wire:model="content_description" rows="4" placeholder="Describe the contents of the parcel in detail"></textarea>
                                            @error('content_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Special Instructions</label>
                                            <textarea class="form-control form-control-lg" wire:model="special_instructions" rows="3"
                                                placeholder="Any special handling instructions (fragile, perishable, etc.)"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing Display - Right Column -->
                            <div class="col-lg-5">
                                <div class="ps-lg-4">
                                    <div class="sticky-top" style="top: 100px;">
                                        <div class="section-divider mb-4">
                                            <h6 class="section-title">
                                                <i class="bi bi-calculator text-success me-2"></i>
                                                Price Calculator
                                            </h6>
                                        </div>

                                        <!-- Price Preview Card - Bolder Design -->
                                        <div class="card border-0 shadow-lg mb-4" style="background: linear-gradient(135deg, #2e7d32, #1b5e20); border-radius: 20px;">
                                            <div class="card-body p-4 text-white">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span class="text-white-75 fw-semibold fs-5">Estimated Total:</span>
                                                    <span class="badge bg-white text-dark rounded-pill px-4 py-3 fs-4 fw-bold shadow">
                                                        <i class="bi bi-cash-stack me-2"></i>
                                                        KES {{ number_format($calculatedPrice, 2) }}
                                                    </span>
                                                </div>
                                                <div class="text-white-50 small fw-medium">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    Price updates automatically as you change values
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Simplified Price Breakdown -->
                                        <div class="card border-0 shadow-lg">
                                            <div class="card-header bg-white py-4 border-0">
                                                <h5 class="fw-bold mb-0">
                                                    <i class="bi bi-pie-chart text-success me-2"></i>
                                                    Price Breakdown
                                                </h5>
                                            </div>
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-2">
                                                    <span class="fw-semibold fs-5">Base Price:</span>
                                                    <span class="fw-bold fs-4 text-success">KES {{ number_format($base_price, 2) }}</span>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-2">
                                                    <span class="fw-semibold fs-5">Tax (16%):</span>
                                                    <span class="fw-bold fs-4 text-success">KES {{ number_format($tax_amount, 2) }}</span>
                                                </div>

                                                @if($discount_amount > 0)
                                                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-2">
                                                    <span class="fw-semibold fs-5 text-danger">Discount:</span>
                                                    <span class="fw-bold fs-4 text-danger">-KES {{ number_format($discount_amount, 2) }}</span>
                                                </div>
                                                @endif

                                                <div class="d-flex justify-content-between align-items-center mt-4 pt-3">
                                                    <span class="fw-bold fs-3">Total:</span>
                                                    <span class="fw-bold text-success display-6">KES {{ number_format($total_amount, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quick Info -->
                                        <div class="mt-4 p-4 bg-success-soft rounded-4 border border-success border-opacity-25">
                                            <small class="text-success-emphasis d-block mb-2 fw-semibold">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                <strong>Base Price:</strong> Based on parcel type and weight
                                            </small>
                                            <small class="text-success-emphasis d-block fw-semibold">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                <strong>Tax:</strong> 16% VAT included
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Step 3: Review & Submit -->
        @if($currentStep == 3)
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-semibold">
                            <i class="bi bi-check2-circle text-primary me-2"></i>
                            Review & Submit
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <!-- Summary Card -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0 text-white" style="background: linear-gradient(135deg, #2e7d32, #1b5e20); border-radius: 20px;">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h6 class="text-white-50 mb-2 fw-semibold">Parcel Number</h6>
                                                <h2 class="text-white mb-0 fw-bold">{{ $parcel_number }}</h2>
                                            </div>
                                            <div class="col-md-6 text-md-end">
                                                <h6 class="text-white-50 mb-2 fw-semibold">Total Amount</h6>
                                                <h1 class="text-white mb-0 fw-bold display-5">KES {{ number_format($total_amount, 2) }}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Sender & Receiver Review -->
                            <div class="col-lg-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h6 class="fw-bold mb-0">
                                            <i class="bi bi-people text-success me-2"></i>
                                            Sender & Receiver
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <h6 class="text-success fw-bold mb-3">Sender Details</h6>
                                            <div class="review-item">
                                                <span class="text-muted fw-medium">Name:</span>
                                                <span class="fw-semibold">{{ $sender_name }}</span>
                                            </div>
                                            <div class="review-item">
                                                <span class="text-muted fw-medium">Phone:</span>
                                                <span class="fw-semibold">{{ $sender_phone }}</span>
                                            </div>
                                            @if($sender_email)
                                            <div class="review-item">
                                                <span class="text-muted fw-medium">Email:</span>
                                                <span class="fw-semibold">{{ $sender_email }}</span>
                                            </div>
                                            @endif
                                            <div class="review-item">
                                                <span class="text-muted fw-medium">Address:</span>
                                                <span class="fw-semibold">{{ $sender_address }}</span>
                                            </div>
                                            <div class="review-item">
                                                <span class="text-muted fw-medium">Pickup Point:</span>
                                                <span class="fw-semibold">
                                                    {{ $pickUpAndDropOffPoints->firstWhere('id', $sender_pick_up_drop_off_point_id)?->name }}
                                                </span>
                                            </div>
                                        </div>

                                        <div>
                                            <h6 class="text-success fw-bold mb-3">Receiver Details</h6>
                                            <div class="review-item">
                                                <span class="text-muted fw-medium">Name:</span>
                                                <span class="fw-semibold">{{ $receiver_name }}</span>
                                            </div>
                                            <div class="review-item">
                                                <span class="text-muted fw-medium">Phone:</span>
                                                <span class="fw-semibold">{{ $receiver_phone }}</span>
                                            </div>
                                            @if($receiver_email)
                                            <div class="review-item">
                                                <span class="text-muted fw-medium">Email:</span>
                                                <span class="fw-semibold">{{ $receiver_email }}</span>
                                            </div>
                                            @endif
                                            <div class="review-item">
                                                <span class="text-muted fw-medium">Address:</span>
                                                <span class="fw-semibold">{{ $receiver_address }}</span>
                                            </div>
                                            <div class="review-item">
                                                <span class="text-muted fw-medium">Delivery Point:</span>
                                                <span class="fw-semibold">
                                                    {{ $pickUpAndDropOffPoints->firstWhere('id', $delivery_pick_up_drop_off_point_id)?->name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Parcel Details Review -->
                            <div class="col-lg-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h6 class="fw-bold mb-0">
                                            <i class="bi bi-box-seam text-success me-2"></i>
                                            Parcel Details
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="bg-success-soft p-3 rounded-3">
                                                    <small class="text-muted fw-medium d-block">Parcel Type</small>
                                                    <span class="fw-bold fs-6">{{ $parcelTypes[$parcel_type]->name }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="bg-success-soft p-3 rounded-3">
                                                    <small class="text-muted fw-medium d-block">Package Type</small>
                                                    <span class="fw-bold fs-6">{{ $packageTypes[$package_type] }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="bg-success-soft p-3 rounded-3">
                                                    <small class="text-muted fw-medium d-block">Weight</small>
                                                    <span class="fw-bold fs-6">{{ $weight }} {{ $weight_unit }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="bg-success-soft p-3 rounded-3">
                                                    <small class="text-muted fw-medium d-block">Declared Value</small>
                                                    <span class="fw-bold fs-6">KES {{ number_format($declared_value, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="bg-success-soft p-3 rounded-3">
                                                    <small class="text-muted fw-medium d-block">Content Description</small>
                                                    <span class="fw-semibold">{{ $content_description }}</span>
                                                </div>
                                            </div>
                                            @if($special_instructions)
                                            <div class="col-12">
                                                <div class="bg-success-soft p-3 rounded-3">
                                                    <small class="text-muted fw-medium d-block">Special Instructions</small>
                                                    <span class="fw-semibold">{{ $special_instructions }}</span>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Simplified Pricing Review -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h6 class="fw-bold mb-0">
                                            <i class="bi bi-cash-coin text-success me-2"></i>
                                            Price Summary
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                            <span class="fw-semibold fs-6">Base Price:</span>
                                            <span class="fw-bold fs-5">KES {{ number_format($base_price, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                            <span class="fw-semibold fs-6">Tax (16%):</span>
                                            <span class="fw-bold fs-5">KES {{ number_format($tax_amount, 2) }}</span>
                                        </div>
                                        @if($discount_amount > 0)
                                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom text-danger">
                                            <span class="fw-semibold fs-6">Discount:</span>
                                            <span class="fw-bold fs-5">-KES {{ number_format($discount_amount, 2) }}</span>
                                        </div>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3">
                                            <span class="fw-bold fs-4">Total:</span>
                                            <span class="fw-bold text-success fs-2">KES {{ number_format($total_amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Options -->
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white py-3">
                                        <h6 class="fw-bold mb-0">
                                            <i class="bi bi-credit-card text-success me-2"></i>
                                            Payment Options
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-4">
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold required">Payment Method</label>
                                                <select class="form-select form-select-lg @error('payment_method') is-invalid @enderror"
                                                    wire:model="payment_method">
                                                    <option value="">Select payment method</option>
                                                    @foreach($paymentMethods as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                @error('payment_method')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-bold">Discount Amount (KES)</label>
                                                <input type="number"
                                                    class="form-control form-control-lg"
                                                    wire:model="discount_amount"
                                                    step="0.01"
                                                    min="0"
                                                    placeholder="0.00">
                                                <small class="text-muted fw-medium">Optional: Apply discount to total amount</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Final Notes -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert border-0 d-flex align-items-center" style="background-color: #e8f5e9; color: #2e7d32;">
                                    <i class="bi bi-info-circle fs-4 me-3"></i>
                                    <div class="fw-medium">
                                        <strong>Important:</strong> After submission, the parcel will be created with status "Pending".
                                        You can track and manage it from the parcels list.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Navigation Buttons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    @if($currentStep > 1)
                    <button class="btn btn-outline-success btn-lg px-5 fw-semibold" wire:click="previousStep">
                        <i class="bi bi-arrow-left me-2"></i>
                        Previous Step
                    </button>
                    @else
                    <div></div>
                    @endif

                    @if($currentStep < $totalSteps)
                        <button class="btn btn-success btn-lg px-5 fw-bold shadow-sm" wire:click="nextStep">
                        Next Step
                        <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                        @else
                        <button class="btn btn-success btn-lg px-5 fw-bold shadow"
                            wire:click="saveParcel"
                            wire:loading.attr="disabled"
                            style="background: linear-gradient(135deg, #2e7d32, #1b5e20);">
                            <span wire:loading.remove wire:target="saveParcel">
                                <i class="bi bi-check-circle me-2"></i>
                                Create Parcel
                            </span>
                            <span wire:loading wire:target="saveParcel">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Creating...
                            </span>
                        </button>
                        @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Modern Green Theme */
        :root {
            --primary-color: #2e7d32;
            --primary-dark: #1b5e20;
            --primary-light: #e8f5e9;
            --primary-soft: #c8e6c9;
            --success-color: #2e7d32;
            --danger-color: #d32f2f;
            --warning-color: #f57c00;
            --info-color: #0288d1;
            --light-bg: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #2e7d32, #1b5e20);
        }

        .bg-success-soft {
            background-color: #e8f5e9;
        }

        .text-success-emphasis {
            color: #2e7d32 !important;
        }

        .border-success {
            border-color: #2e7d32 !important;
        }

        .text-white-50 {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .text-white-75 {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            padding: 20px 0;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .step-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: white;
            border: 3px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #94a3b8;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .step-item.active .step-icon {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 10px 20px rgba(46, 125, 50, 0.2);
        }

        .step-item.completed .step-icon {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }

        .step-label {
            font-size: 14px;
            font-weight: 500;
            color: #64748b;
        }

        .step-item.active .step-label {
            color: var(--primary-color);
            font-weight: 600;
        }

        .step-item.completed .step-label {
            color: var(--primary-color);
        }

        .step-connector {
            flex: 1;
            height: 3px;
            background: #e2e8f0;
            max-width: 100px;
            transition: all 0.3s ease;
        }

        .step-connector.active {
            background: var(--primary-color);
        }

        /* Form Styles */
        .form-label {
            font-size: 14px;
            margin-bottom: 6px;
            color: #1e293b;
            font-weight: 500;
        }

        .form-label.required::after {
            content: " *";
            color: var(--danger-color);
            font-weight: bold;
        }

        .form-control,
        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control-lg,
        .form-select-lg {
            padding: 14px 18px;
            font-size: 16px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.1);
            outline: none;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            font-size: 12px;
            color: var(--danger-color);
            margin-top: 5px;
        }

        /* Info Sections */
        .info-section {
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .info-section:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border-color: var(--primary-color);
        }

        .section-title {
            color: #1e293b;
            font-size: 16px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }

        /* Review Items */
        .review-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .review-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        /* Buttons */
        .btn {
            border-radius: 12px;
            padding: 14px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-lg {
            padding: 16px 36px;
            font-size: 16px;
        }

        .btn-success {
            background: var(--primary-color);
            border: none;
            box-shadow: 0 4px 6px rgba(46, 125, 50, 0.2);
        }

        .btn-success:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(46, 125, 50, 0.3);
        }

        .btn-outline-success {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-success:hover {
            background: var(--primary-light);
            border-color: var(--primary-dark);
            color: var(--primary-dark);
        }

        /* Cards */
        .card {
            border-radius: 16px;
            overflow: hidden;
        }

        .card-header {
            border-bottom: 1px solid #e2e8f0;
            background: white;
        }

        /* Alerts */
        .alert {
            border-radius: 12px;
            padding: 16px 20px;
        }

        /* Loading States */
        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner-border {
            width: 20px;
            height: 20px;
            margin-right: 8px;
        }

        /* Sticky */
        .sticky-top {
            z-index: 1020;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .progress-steps {
                flex-direction: column;
                gap: 15px;
            }

            .step-connector {
                width: 3px;
                height: 30px;
                max-width: none;
            }

            .step-item {
                width: 100%;
            }

            .btn {
                width: 100%;
                padding: 12px 20px;
            }

            .btn-lg {
                padding: 14px 24px;
            }

            .display-5 {
                font-size: 2rem;
            }

            .display-6 {
                font-size: 1.75rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card,
        .info-section {
            animation: fadeIn 0.5s ease-out;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Additional Bold Styles */
        .fw-bold {
            font-weight: 700 !important;
        }

        .fw-semibold {
            font-weight: 600 !important;
        }

        .fw-medium {
            font-weight: 500 !important;
        }

        .display-5 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .display-6 {
            font-size: 2rem;
            font-weight: 700;
        }

        .fs-2 {
            font-size: 1.5rem !important;
        }

        .fs-3 {
            font-size: 1.25rem !important;
        }

        .fs-4 {
            font-size: 1.125rem !important;
        }

        .fs-5 {
            font-size: 1rem !important;
        }

        .fs-6 {
            font-size: 0.875rem !important;
        }
    </style>
</div>