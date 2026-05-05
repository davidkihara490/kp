<div>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Create New Partner</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.partners.index') }}">Partners</a>
                            </li>
                            <li class="breadcrumb-item active">Create Partner</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <form wire:submit.prevent="save" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Main Content -->
                        <div class="col-md-12">
                            <!-- Owner Information -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Owner Information</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="owner_id">Existing User (Optional)</label>
                                                <select class="form-control @error('owner_id') is-invalid @enderror"
                                                    id="owner_id"
                                                    wire:model="owner_id">
                                                    <option value="">Select existing user</option>
                                                    @foreach($existingUsers as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                    @endforeach
                                                </select>
                                                @error('owner_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Basic Information -->
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Basic Information</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="partner_type">Partner Type</label>
                                                <select class="form-control @error('partner_type') is-invalid @enderror"
                                                    id="partner_type"
                                                    wire:model="partner_type">
                                                    <option value="">Select type</option>
                                                    @foreach(['transport', 'pickup-dropoff'] as $type)
                                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                                    @endforeach
                                                </select>
                                                @error('partner_type')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="company_name">Company Name <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('company_name') is-invalid @enderror"
                                                    id="company_name"
                                                    wire:model="company_name"
                                                    placeholder="Enter company name">
                                                @error('company_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="registration_number">Registration Number <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('registration_number') is-invalid @enderror"
                                                    id="registration_number"
                                                    wire:model="registration_number"
                                                    placeholder="Enter registration number">
                                                @error('registration_number')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kra_pin">KRA PIN <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('kra_pin') is-invalid @enderror"
                                                    id="kra_pin"
                                                    wire:model="kra_pin"
                                                    placeholder="Enter KRA PIN">
                                                @error('kra_pin')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="verification_status">Verification Status</label>
                                                <select class="form-control @error('verification_status') is-invalid @enderror"
                                                    id="verification_status"
                                                    wire:model="verification_status">
                                                    <option value="pending">Pending</option>
                                                    <option value="verified">Verified</option>
                                                    <option value="rejected">Rejected</option>
                                                    <option value="suspended">Suspended</option>
                                                </select>
                                                @error('verification_status')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="card">
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            <i class="fas fa-save"></i> Create Partner
                                        </span>
                                        <span wire:loading>
                                            <i class="fas fa-spinner fa-spin"></i> Creating...
                                        </span>
                                    </button>
                                    <a href="{{ route('admin.partners.index') }}" class="btn btn-default">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>