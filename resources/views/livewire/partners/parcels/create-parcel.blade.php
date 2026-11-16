<div>
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 bg-gradient-primary text-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h2 class="display-6 fw-bold mb-2">Create New Parcel</h2>
                                <p class="mb-0 opacity-75">Complete 5 quick steps to book a parcel</p>
                            </div>
                            <a href="{{ route('partners.parcels.index') }}" class="btn btn-light btn-lg rounded-pill px-4">
                                <i class="bi bi-arrow-left me-2"></i> Back to Parcels
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="progress-steps">
                    @php
                        $steps = [
                            1 => ['label' => 'Route',           'icon' => 'bi-geo-alt'],
                            2 => ['label' => 'Items',           'icon' => 'bi-box-seam'],
                            3 => ['label' => 'Courier',         'icon' => 'bi-truck'],
                            4 => ['label' => 'PUDO & Contacts', 'icon' => 'bi-people'],
                            5 => ['label' => 'Review',          'icon' => 'bi-check2-circle'],
                        ];
                    @endphp
                    @foreach($steps as $n => $s)
                        <div class="step-item {{ $currentStep >= $n ? 'active' : '' }} {{ $currentStep > $n ? 'completed' : '' }}">
                            <div class="step-icon"><i class="bi {{ $s['icon'] }}"></i></div>
                            <div class="step-label">{{ $s['label'] }}</div>
                        </div>
                        @if($n < $totalSteps)
                            <div class="step-connector {{ $currentStep > $n ? 'active' : '' }}"></div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        @if($errors->has('general'))
            <div class="alert alert-danger">{{ $errors->first('general') }}</div>
        @endif

        {{-- STEP 1: Route --}}
        @if($currentStep === 1)
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-geo-alt text-primary me-2"></i> Select your route
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="info-section p-4">
                                        <h6 class="section-title fw-semibold mb-4">
                                            <i class="bi bi-box-arrow-up text-primary me-2"></i> From
                                        </h6>
                                        <label class="form-label required">Sender Town</label>
                                        <select class="form-select @error('sender_town_id') is-invalid @enderror"
                                                wire:model.live="sender_town_id">
                                            <option value="">Select town</option>
                                            @foreach($senderTowns as $town)
                                                <option value="{{ $town->id }}">{{ $town->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('sender_town_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="info-section p-4">
                                        <h6 class="section-title fw-semibold mb-4">
                                            <i class="bi bi-box-arrow-down text-success me-2"></i> To
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label required">Receiver County</label>
                                                <select class="form-select @error('receiver_county_id') is-invalid @enderror"
                                                        wire:model.live="receiver_county_id">
                                                    <option value="">Select county</option>
                                                    @foreach($counties as $county)
                                                        <option value="{{ $county->id }}">{{ $county->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('receiver_county_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label required">Receiver Town</label>
                                                <select class="form-select @error('receiver_town_id') is-invalid @enderror"
                                                        wire:model.live="receiver_town_id">
                                                    <option value="">Select town</option>
                                                    @foreach($countyTowns as $town)
                                                        <option value="{{ $town->id }}">{{ $town->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('receiver_town_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

        {{-- STEP 2: Items --}}
        @if($currentStep === 2)
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-box-seam text-primary me-2"></i> Items in this shipment
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @error('parcelItems')<div class="alert alert-danger py-2">{{ $message }}</div>@enderror

                            @foreach($parcelItems as $index => $row)
                                <div class="item-card mb-4">
                                    <span class="item-number">Item #{{ $index + 1 }}</span>
                                    @if(count($parcelItems) > 1)
                                        <button type="button" class="remove-item-btn"
                                                wire:click="removeItemRow({{ $index }})" title="Remove">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                    @endif
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label required">Parcel Category</label>
                                            <select class="form-select @error("parcelItems.$index.parcel_category_id") is-invalid @enderror"
                                                    wire:model.live="parcelItems.{{ $index }}.parcel_category_id">
                                                <option value="">— Select category —</option>
                                                @foreach($catalogueItems as $catalogue)
                                                    <option value="{{ $catalogue->id }}">{{ $catalogue->name }}</option>
                                                @endforeach
                                            </select>
                                            @error("parcelItems.$index.parcel_category_id")<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required">Package Type</label>
                                            <select class="form-select @error("parcelItems.$index.package_type") is-invalid @enderror"
                                                    wire:model.live="parcelItems.{{ $index }}.package_type">
                                                @foreach($packageTypes as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error("parcelItems.$index.package_type")<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label required">Content Description</label>
                                            <input type="text" class="form-control @error("parcelItems.$index.content_description") is-invalid @enderror"
                                                   wire:model="parcelItems.{{ $index }}.content_description"
                                                   placeholder="Describe the item">
                                            @error("parcelItems.$index.content_description")<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Declared Value (KES)
                                                @if(!empty($row['payment_on_delivery']))<span class="text-danger">*</span>@endif
                                            </label>
                                            <input type="number" min="0" step="1"
                                                   class="form-control @error("parcelItems.$index.declared_value") is-invalid @enderror"
                                                   wire:model.live.debounce.500ms="parcelItems.{{ $index }}.declared_value"
                                                   placeholder="0">
                                            @error("parcelItems.$index.declared_value")<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                Special Notes
                                                @if(!empty($row['payment_on_delivery']))<span class="text-danger">*</span>@endif
                                            </label>
                                            <input type="text" class="form-control @error("parcelItems.$index.special_notes") is-invalid @enderror"
                                                   wire:model="parcelItems.{{ $index }}.special_notes"
                                                   placeholder="{{ !empty($row['payment_on_delivery']) ? 'Payment details: amount, method, etc.' : 'Any special notes' }}">
                                            @error("parcelItems.$index.special_notes")<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check form-check-lg p-3 bg-light rounded-3 mb-2">
                                                <input class="form-check-input" type="checkbox" id="insurance_{{ $index }}"
                                                       wire:model.live="parcelItems.{{ $index }}.insurance_required">
                                                <label class="form-check-label fw-semibold ms-2" for="insurance_{{ $index }}">
                                                    <i class="bi bi-shield-shaded text-success me-2"></i>
                                                    Add insurance (2% of declared value)
                                                </label>
                                            </div>
                                            <div class="form-check form-check-lg p-3 bg-light rounded-3">
                                                <input class="form-check-input" type="checkbox" id="pod_{{ $index }}"
                                                       wire:model.live="parcelItems.{{ $index }}.payment_on_delivery">
                                                <label class="form-check-label fw-semibold ms-2" for="pod_{{ $index }}">
                                                    <i class="bi bi-cash-coin text-warning me-2"></i>
                                                    Customer will pay on delivery
                                                </label>
                                                <small class="text-muted d-block ms-4 mt-1">
                                                    If checked, declared value and special notes become mandatory.
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <button type="button" class="add-item-btn" wire:click="addItemRow">
                                <i class="bi bi-plus-circle me-2"></i> Add another item
                            </button>

                            <div class="alert border-0 mt-4" style="background-color:#e8f5e9;color:#2e7d32;">
                                <i class="bi bi-info-circle me-2"></i>
                                Prices will be calculated once you select a courier in the next step.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- STEP 3: Transport Partner --}}
        @if($currentStep === 3)
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-truck text-primary me-2"></i> Compare & select a transport partner
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @if(empty($couriers))
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-exclamation-circle fs-1"></i>
                                    <p class="mt-3 mb-0">No transport partner currently serves this route for the selected items.</p>
                                </div>
                            @else
                                @error('transport_partner_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                @foreach($couriers as $courier)
                                    <label class="courier-card {{ $transport_partner_id == $courier['id'] ? 'selected' : '' }}"
                                           wire:key="courier-{{ $courier['id'] }}">
                                        <input type="radio" name="courier" value="{{ $courier['id'] }}"
                                               wire:model.live="transport_partner_id" class="d-none">
                                        <div class="courier-logo">
                                            @if(!empty($courier['logo']))
                                                <img src="{{ $courier['logo'] }}" alt="{{ $courier['name'] }}">
                                            @else
                                                <i class="bi bi-truck"></i>
                                            @endif
                                        </div>
                                        <div class="courier-info">
                                            <h6 class="mb-1 fw-bold">{{ $courier['name'] }}</h6>
                                            @if(!empty($courier['description']))
                                                <div class="text-muted small">{{ $courier['description'] }}</div>
                                            @endif
                                            <div class="courier-route">
                                                <i class="bi bi-geo-alt-fill text-success"></i>
                                                {{ optional(\App\Models\Town::find($sender_town_id))->name }}
                                                <i class="bi bi-arrow-right mx-1"></i>
                                                <i class="bi bi-geo-alt-fill text-danger"></i>
                                                {{ optional(\App\Models\Town::find($receiver_town_id))->name }}
                                            </div>
                                        </div>
                                        <div class="courier-price">
                                            <div class="fw-bold fs-4 text-success">KES {{ number_format($courier['total'], 0) }}</div>
                                            <div class="text-muted small">includes fees & tax</div>
                                        </div>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- STEP 4: PUDO + Sender/Receiver --}}
        @if($currentStep === 4)
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="bi bi-geo-alt text-primary me-2"></i> PUDO points & contact details
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                {{-- Sender --}}
                                <div class="col-lg-6">
                                    <div class="info-section p-4">
                                        <h6 class="section-title fw-semibold mb-4">
                                            <i class="bi bi-person-up text-primary me-2"></i> Sender
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label required">Full Name</label>
                                                <input type="text" class="form-control @error('sender_name') is-invalid @enderror"
                                                       wire:model="sender_name" placeholder="Sender full name">
                                                @error('sender_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label required">Phone</label>
                                                <input type="text" class="form-control @error('sender_phone') is-invalid @enderror"
                                                       wire:model="sender_phone" placeholder="07XX XXX XXX">
                                                @error('sender_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control @error('sender_email') is-invalid @enderror"
                                                       wire:model="sender_email" placeholder="email@example.com">
                                                @error('sender_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label required">Pickup Station</label>
                                                <select class="form-select @error('sender_pick_up_drop_off_point_id') is-invalid @enderror"
                                                        wire:model="sender_pick_up_drop_off_point_id">
                                                    <option value="">Select pickup station</option>
                                                    @foreach($senderPickUpAndDropOffPoints as $point)
                                                        <option value="{{ $point->id }}">
                                                            {{ $point->name }}@if($point->address) — {{ $point->address }}@endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('sender_pick_up_drop_off_point_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Receiver --}}
                                <div class="col-lg-6">
                                    <div class="info-section p-4">
                                        <h6 class="section-title fw-semibold mb-4">
                                            <i class="bi bi-person-down text-success me-2"></i> Receiver
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label required">Full Name</label>
                                                <input type="text" class="form-control @error('receiver_name') is-invalid @enderror"
                                                       wire:model="receiver_name" placeholder="Receiver full name">
                                                @error('receiver_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label required">Phone</label>
                                                <input type="text" class="form-control @error('receiver_phone') is-invalid @enderror"
                                                       wire:model="receiver_phone" placeholder="07XX XXX XXX">
                                                @error('receiver_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control @error('receiver_email') is-invalid @enderror"
                                                       wire:model="receiver_email" placeholder="email@example.com">
                                                @error('receiver_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label required">Delivery Station</label>
                                                <select class="form-select @error('delivery_pick_up_drop_off_point_id') is-invalid @enderror"
                                                        wire:model="delivery_pick_up_drop_off_point_id">
                                                    <option value="">Select delivery station</option>
                                                    @foreach($receiverPickUpAndDropOffPoints as $point)
                                                        <option value="{{ $point->id }}">
                                                            {{ $point->name }}@if($point->address) — {{ $point->address }}@endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('delivery_pick_up_drop_off_point_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

        {{-- STEP 5: Review & Confirm --}}
        @if($currentStep === 5)
            @php
                $totals = $selectedBreakdown['totals'] ?? [];
                $fromTownName = optional(\App\Models\Town::find($sender_town_id))->name;
                $toTownName   = optional(\App\Models\Town::find($receiver_town_id))->name;
                $senderPoint  = $pickUpAndDropOffPoints->firstWhere('id', $sender_pick_up_drop_off_point_id);
                $deliveryPoint= $pickUpAndDropOffPoints->firstWhere('id', $delivery_pick_up_drop_off_point_id);
                $courier      = collect($couriers)->firstWhere('id', $transport_partner_id);
            @endphp

            <div class="row g-4">
                <div class="col-12">
                    <div class="card border-0 text-white" style="background:linear-gradient(135deg,#2e7d32,#1b5e20);border-radius:20px;">
                        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <div class="text-white-50 small fw-semibold mb-1">Parcel Number</div>
                                <div class="fw-bold fs-4">{{ $parcel_number }}</div>
                            </div>
                            <div class="text-md-end">
                                <div class="text-white-50 small fw-semibold mb-1">Total Amount</div>
                                <div class="fw-bold display-6">KES {{ number_format($totals['total'] ?? 0, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0"><i class="bi bi-people text-success me-2"></i> Sender & Receiver</h6>
                        </div>
                        <div class="card-body">
                            <h6 class="text-success fw-bold mb-3">Sender</h6>
                            <div class="review-item"><span class="text-muted">Name</span><span class="fw-semibold">{{ $sender_name }}</span></div>
                            <div class="review-item"><span class="text-muted">Phone</span><span class="fw-semibold">{{ $sender_phone }}</span></div>
                            <div class="review-item"><span class="text-muted">Pickup</span><span class="fw-semibold">{{ $senderPoint?->name ?? '—' }}</span></div>

                            <h6 class="text-success fw-bold mb-3 mt-4">Receiver</h6>
                            <div class="review-item"><span class="text-muted">Name</span><span class="fw-semibold">{{ $receiver_name }}</span></div>
                            <div class="review-item"><span class="text-muted">Phone</span><span class="fw-semibold">{{ $receiver_phone }}</span></div>
                            <div class="review-item"><span class="text-muted">Delivery</span><span class="fw-semibold">{{ $deliveryPoint?->name ?? '—' }}</span></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0"><i class="bi bi-box-seam text-success me-2"></i> Items ({{ count($parcelItems) }})</h6>
                        </div>
                        <div class="card-body">
                            @foreach($parcelItems as $i => $row)
                                @php
                                    $cat = collect($catalogueItems)->firstWhere('id', (int) ($row['parcel_category_id'] ?? 0));
                                    $priced = $selectedBreakdown['items'][$i] ?? null;
                                @endphp
                                <div class="p-3 rounded-3 mb-3" style="background:#f1f8e9;">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <strong>Item #{{ $i + 1 }}: {{ $cat->name ?? '—' }}</strong>
                                        <span class="fw-bold text-success">KES {{ number_format($priced['total'] ?? 0, 2) }}</span>
                                    </div>
                                    <small class="text-muted d-block">{{ $row['content_description'] }}</small>
                                    <small class="text-muted d-block">
                                        Package: {{ $packageTypes[$row['package_type']] ?? $row['package_type'] }}
                                        · Declared: KES {{ number_format((float) ($row['declared_value'] ?? 0), 0) }}
                                    </small>
                                    @if(!empty($row['insurance_required']))
                                        <small class="text-success d-block">
                                            <i class="bi bi-shield-check me-1"></i>
                                            Insurance: KES {{ number_format($priced['insurance'] ?? 0, 2) }}
                                        </small>
                                    @endif
                                    @if(!empty($row['payment_on_delivery']))
                                        <small class="text-danger fw-semibold d-block">
                                            <i class="bi bi-cash-coin me-1"></i> Payment on Delivery
                                        </small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0"><i class="bi bi-truck text-success me-2"></i> Courier</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="courier-logo">
                                    @if(!empty($courier['logo']))
                                        <img src="{{ $courier['logo'] }}" alt="{{ $courier['name'] }}">
                                    @else
                                        <i class="bi bi-truck"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $courier['name'] ?? '—' }}</div>
                                    <div class="text-muted small">{{ $fromTownName }} → {{ $toTownName }}</div>
                                </div>
                            </div>

                            <div class="review-item"><span class="text-muted">Courier price</span><span class="fw-semibold">KES {{ number_format($totals['courier_price'] ?? 0, 2) }}</span></div>
                            <div class="review-item"><span class="text-muted">PUDO fees</span><span class="fw-semibold">KES {{ number_format($totals['pudo_amount'] ?? 0, 2) }}</span></div>
                            <div class="review-item"><span class="text-muted">Transport fees</span><span class="fw-semibold">KES {{ number_format($totals['transport_fee'] ?? 0, 2) }}</span></div>
                            <div class="review-item"><span class="text-muted">Platform fees</span><span class="fw-semibold">KES {{ number_format($totals['platform_amount'] ?? 0, 2) }}</span></div>
                            <div class="review-item"><span class="text-muted">Insurance</span><span class="fw-semibold">KES {{ number_format($totals['insurance'] ?? 0, 2) }}</span></div>
                            <div class="review-item"><span class="text-muted">Tax</span><span class="fw-semibold">KES {{ number_format($totals['tax_amount'] ?? 0, 2) }}</span></div>
                            <div class="review-item border-0 mt-2 pt-3">
                                <span class="fw-bold fs-5">Total</span>
                                <span class="fw-bold text-success fs-4">KES {{ number_format($totals['total'] ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0"><i class="bi bi-credit-card text-success me-2"></i> Payment</h6>
                        </div>
                        <div class="card-body">
                            <label class="form-label required">Payment Method</label>
                            <select class="form-select form-select-lg" wire:model="payment_method">
                                @foreach($paymentMethods as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Navigation --}}
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                @if($currentStep > 1)
                    <button class="btn btn-outline-success btn-lg px-5 fw-semibold" wire:click="previousStep">
                        <i class="bi bi-arrow-left me-2"></i> Previous
                    </button>
                @else
                    <div></div>
                @endif

                @if($currentStep < $totalSteps)
                    <button class="btn btn-success btn-lg px-5 fw-bold shadow-sm" wire:click="nextStep">
                        Next <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                @else
                    <button class="btn btn-success btn-lg px-5 fw-bold shadow"
                            wire:click="saveParcel" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="saveParcel">
                            <i class="bi bi-check-circle me-2"></i> Create Parcel
                        </span>
                        <span wire:loading wire:target="saveParcel">
                            <span class="spinner-border spinner-border-sm me-2"></span> Creating…
                        </span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-color:#2e7d32; --primary-dark:#1b5e20; --primary-light:#e8f5e9;
            --danger-color:#d32f2f; --warning-color:#f57c00;
            --text-dark:#1e293b; --text-muted:#64748b; --border-color:#e2e8f0;
        }
        .bg-gradient-primary { background:linear-gradient(135deg,#2e7d32,#1b5e20); }
        .text-white-50 { color:rgba(255,255,255,.7)!important; }

        .progress-steps { display:flex; align-items:center; justify-content:center; gap:14px; padding:20px 0; }
        .step-item { display:flex; flex-direction:column; align-items:center; }
        .step-icon { width:54px; height:54px; border-radius:50%; background:#fff;
            border:3px solid #e2e8f0; display:flex; align-items:center; justify-content:center;
            font-size:22px; color:#94a3b8; margin-bottom:8px; transition:.3s; }
        .step-item.active .step-icon { border-color:var(--primary-color); background:var(--primary-color);
            color:#fff; transform:scale(1.05); box-shadow:0 10px 20px rgba(46,125,50,.2); }
        .step-item.completed .step-icon { border-color:var(--primary-color); background:var(--primary-color); color:#fff; }
        .step-label { font-size:12px; font-weight:500; color:#64748b; text-align:center; }
        .step-item.active .step-label, .step-item.completed .step-label { color:var(--primary-color); }
        .step-connector { flex:1; max-width:70px; height:3px; background:#e2e8f0; transition:.3s; }
        .step-connector.active { background:var(--primary-color); }

        .form-label { font-size:13px; margin-bottom:6px; font-weight:500; color:var(--text-dark); }
        .form-label.required::after { content:" *"; color:var(--danger-color); font-weight:bold; }
        .form-control, .form-select { border:2px solid var(--border-color); border-radius:12px;
            padding:10px 14px; font-size:14px; transition:.3s; }
        .form-control:focus, .form-select:focus { border-color:var(--primary-color); box-shadow:0 0 0 4px rgba(46,125,50,.1); }
        .form-control.is-invalid, .form-select.is-invalid { border-color:var(--danger-color); }
        .invalid-feedback { font-size:12px; color:var(--danger-color); margin-top:4px; }

        .info-section { background:#f8fafc; border:1px solid var(--border-color); border-radius:16px; transition:.3s; }
        .info-section:hover { border-color:var(--primary-color); box-shadow:0 10px 30px rgba(0,0,0,.05); }
        .section-title { font-size:16px; margin-bottom:20px; padding-bottom:10px; border-bottom:2px solid var(--border-color); }

        .item-card { position:relative; background:#f8fafc; border:2px solid var(--border-color);
            border-radius:16px; padding:1.5rem 1rem 1rem; transition:.3s; }
        .item-card:hover { border-color:var(--primary-color); box-shadow:0 8px 24px rgba(46,125,50,.08); }
        .item-card .item-number { position:absolute; top:-12px; left:16px; background:var(--primary-color);
            color:#fff; padding:.25rem .85rem; border-radius:20px; font-size:.75rem; font-weight:700; }
        .item-card .remove-item-btn { position:absolute; top:8px; right:8px; background:none; border:none;
            color:var(--danger-color); font-size:1.25rem; cursor:pointer; border-radius:50%; padding:4px 8px; transition:.2s; }
        .item-card .remove-item-btn:hover { background:#fee2e2; transform:scale(1.1); }

        /* Greenish Add-Item button */
        .add-item-btn {
            width:100%; padding:14px; border:2px dashed var(--primary-color); border-radius:16px;
            background:#e8f5e9; color:var(--primary-dark); font-weight:600; cursor:pointer;
            transition:.3s; display:flex; align-items:center; justify-content:center;
        }
        .add-item-btn:hover { background:#c8e6c9; color:var(--primary-dark);
            box-shadow:0 4px 12px rgba(46,125,50,.15); transform:translateY(-1px); }

        .courier-card { display:flex; align-items:center; gap:16px; padding:16px 20px;
            border:2px solid var(--border-color); border-radius:16px; background:#fff; cursor:pointer;
            margin-bottom:12px; transition:.15s; }
        .courier-card:hover { border-color:#c7dbd3; background:#fbfefc; }
        .courier-card.selected { border-color:var(--primary-color); background:var(--primary-light);
            box-shadow:0 4px 14px rgba(46,125,50,.12); }
        .courier-logo { width:52px; height:52px; border-radius:12px; background:var(--primary-light);
            color:var(--primary-color); display:flex; align-items:center; justify-content:center;
            font-size:1.5rem; flex-shrink:0; overflow:hidden; }
        .courier-logo img { width:100%; height:100%; object-fit:cover; }
        .courier-info { flex:1; min-width:0; }
        .courier-route { font-size:.8rem; color:var(--text-muted); margin-top:4px; }
        .courier-price { text-align:right; flex-shrink:0; }

        .review-item { display:flex; justify-content:space-between; align-items:flex-start;
            padding:8px 0; border-bottom:1px dashed #e2e8f0; }
        .review-item:last-child { border-bottom:none; }

        .btn { border-radius:12px; padding:12px 28px; font-weight:500; transition:.3s; }
        .btn-lg { padding:14px 32px; font-size:15px; }
        .btn-success { background:var(--primary-color); border:none; box-shadow:0 4px 6px rgba(46,125,50,.2); }
        .btn-success:hover { background:var(--primary-dark); transform:translateY(-1px); box-shadow:0 6px 12px rgba(46,125,50,.3); }
        .btn-outline-success { border:2px solid var(--primary-color); color:var(--primary-color); }
        .btn-outline-success:hover { background:var(--primary-light); color:var(--primary-dark); }
        .card { border-radius:16px; overflow:hidden; }
        .alert { border-radius:12px; padding:16px 20px; }

        @media (max-width: 768px) {
            .progress-steps { flex-wrap:wrap; gap:8px; }
            .step-connector { display:none; }
            .courier-card { flex-wrap:wrap; }
            .courier-price { width:100%; text-align:left; }
            .btn { width:100%; }
        }
    </style>
</div>