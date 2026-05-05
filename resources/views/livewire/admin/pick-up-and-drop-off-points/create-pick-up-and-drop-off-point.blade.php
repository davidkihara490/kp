<div>
    <div></div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Create Pick-up & Drop-off Point</h3>
                        </div>

                        <form wire:submit.prevent="save">
                            <div class="card-body">
                                <!-- Basic Information Section -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="partner_id">Partner <span class="text-danger">*</span></label>
                                            <select class="form-control @error('partner_id') is-invalid @enderror"
                                                id="partner_id"
                                                wire:model="partner_id">
                                                <option value="">Select Partner</option>
                                                @foreach($partners as $partner)
                                                <option value="{{ $partner->id }}">{{ $partner->company_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('partner_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Point Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                id="name"
                                                wire:model="name"
                                                placeholder="Enter point name (e.g., Downtown Hub)">
                                            @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="town_id">Town <span class="text-danger">*</span></label>
                                            <select class="form-control @error('town_id') is-invalid @enderror"
                                                id="town_id"
                                                wire:model="town_id">
                                                <option value="">Select Town</option>
                                                @foreach($towns as $town)
                                                <option value="{{ $town->id }}">{{ $town->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('town_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Type <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror"
                                                id="type"
                                                wire:model="type">
                                                <option value="warehouse">Warehouse</option>
                                                <option value="pickup-dropoff">PickUp/DropOff</option>
                                            </select>
                                            @error('type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Details Section -->
                                <h5 class="mt-4 mb-3">Location Details</h5>
                                <hr>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text"
                                                class="form-control @error('address') is-invalid @enderror"
                                                id="address"
                                                wire:model="address"
                                                placeholder="Address">
                                            @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">Status <span class="text-danger">*</span></label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status"
                                                wire:model="status">
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                            @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Coordinates Section -->
                                <h5 class="mt-4 mb-3">GPS Coordinates (Optional)</h5>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="latitude">Latitude</label>
                                            <input type="number" step="any"
                                                class="form-control @error('latitude') is-invalid @enderror"
                                                id="latitude"
                                                wire:model="latitude"
                                                placeholder="e.g., -1.286389">
                                            @error('latitude')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small class="form-text text-muted">Enter decimal degrees (e.g., -1.286389)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="longitude">Longitude</label>
                                            <input type="number" step="any"
                                                class="form-control @error('longitude') is-invalid @enderror"
                                                id="longitude"
                                                wire:model="longitude"
                                                placeholder="e.g., 36.817223">
                                            @error('longitude')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small class="form-text text-muted">Enter decimal degrees (e.g., 36.817223)</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information Section -->
                                <h5 class="mt-4 mb-3">Contact Information</h5>
                                <hr>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_person">Contact Person</label>
                                            <input type="text"
                                                class="form-control @error('contact_person') is-invalid @enderror"
                                                id="contact_person"
                                                wire:model="contact_person"
                                                placeholder="Name of contact person">
                                            @error('contact_person')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_email">Contact Email</label>
                                            <input type="email"
                                                class="form-control @error('contact_email') is-invalid @enderror"
                                                id="contact_email"
                                                wire:model="contact_email"
                                                placeholder="contact@example.com">
                                            @error('contact_email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_phone_number">Contact Phone Number</label>
                                            <input type="tel"
                                                class="form-control @error('contact_phone_number') is-invalid @enderror"
                                                id="contact_phone_number"
                                                wire:model="contact_phone_number"
                                                placeholder="Enter phone number">
                                            @error('contact_phone_number')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="fas fa-save"></i> Create Point
                                    </span>
                                    <span wire:loading>
                                        <i class="fas fa-spinner fa-spin"></i> Creating...
                                    </span>
                                </button>
                                <a href="{{ route('admin.points.index') }}" class="btn btn-default">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>