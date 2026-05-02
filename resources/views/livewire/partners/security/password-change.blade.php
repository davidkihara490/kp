<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="bi bi-key me-2"></i>Change Password
            </h3>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <form wire:submit.prevent="submit">
                <!-- Current Password -->
                <div class="card card-primary mb-4" style="border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="card-header" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h3 class="card-title">
                            <i class="bi bi-shield-lock me-2"></i>Authentication
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Current Password *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                        </div>
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            wire:model="current_password"
                                            placeholder="Enter current password"
                                            autocomplete="current-password">
                                    </div>
                                    @error('current_password')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New Password -->
                <div class="card card-success mb-4" style="border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="card-header" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h3 class="card-title">
                            <i class="bi bi-plus-circle me-2"></i>New Password
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>New Password *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="bi bi-key"></i>
                                            </span>
                                        </div>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            wire:model="password"
                                            placeholder="Enter new password"
                                            autocomplete="new-password">
                                    </div>
                                    @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Password must be at least 8 characters long
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirm New Password *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="bi bi-check-circle"></i>
                                            </span>
                                        </div>
                                        <input type="password"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            wire:model="password_confirmation"
                                            placeholder="Confirm new password"
                                            autocomplete="new-password">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                                wire:target="submit">
                                <span wire:loading.remove wire:target="submit">
                                    <i class="bi bi-save me-2"></i>Change Password
                                </span>
                                <span wire:loading wire:target="submit">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    Updating...
                                </span>
                            </button>
                            <button type="reset" class="btn btn-secondary" onclick="resetForm()">
                                <i class="bi bi-arrow-repeat me-2"></i>Reset
                            </button>
                            <div class="float-right">
                                <span class="text-muted">
                                    <i class="bi bi-lock me-1"></i>
                                    Fields marked with * are required
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        margin-bottom: 30px !important;
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
    }

    .card-header {
        padding: 15px 20px;
        background-color: rgba(0, 0, 0, 0.02);
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 25px 20px;
    }

    .card-title {
        margin-bottom: 0;
        font-weight: 600;
    }

    /* Card color variations */
    .card-primary {
        border-left: 4px solid #007bff;
    }

    .card-success {
        border-left: 4px solid #28a745;
    }

    .card-gray {
        border-left: 4px solid #6c757d;
    }

    /* Form styling */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 500;
        margin-bottom: 8px;
        color: #495057;
    }

    .form-control {
        padding: 10px 15px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Alert styling */
    .alert {
        border-radius: 8px;
        border: 1px solid transparent;
        padding: 15px;
        margin-bottom: 0;
    }

    /* Button styling */
    .btn {
        padding: 10px 25px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Input group styling */
    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-right: none;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group .form-control:focus {
        border-color: #80bdff;
        box-shadow: none;
    }

    .input-group-append .btn {
        border-radius: 0 6px 6px 0;
        border-left: none;
    }

    /* Row spacing */
    .row {
        margin-bottom: 10px;
    }

    .row.mt-3 {
        margin-top: 20px !important;
    }

    /* Footer spacing */
    .card-footer {
        padding: 20px;
        background-color: rgba(0, 0, 0, 0.02);
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    /* List styling */
    ul {
        padding-left: 20px;
    }

    ul li {
        margin-bottom: 5px;
    }

    /* Bootstrap Icons spacing */
    .bi {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
    // Reset form function
    function resetForm() {
        if (confirm('Are you sure you want to reset the form?')) {
            @this.reset(['current_password', 'password', 'password_confirmation']);
            @this.resetErrorBag();
        }
    }
</script>
@endpush