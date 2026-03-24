<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                <i class="fas fa-file-contract mr-2"></i>Create Terms & Conditions
            </h3>
            <a href="{{ route('admin.terms') }}" class="btn btn-secondary btn-sm float-right">
                <i class="fas fa-arrow-left mr-2"></i>Back to Terms
            </a>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <form wire:submit.prevent="save">
                <div class="row">
                    <!-- Left Column - Main Content -->
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle mr-2"></i>Basic Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="title">Document Title *</label>
                                            <input type="text"
                                                class="form-control @error('title') is-invalid @enderror"
                                                id="title"
                                                wire:model="title"
                                                placeholder="e.g., Terms of Service, Terms of Use">
                                            @error('title')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="version">Version *</label>
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control @error('version') is-invalid @enderror"
                                                    id="version"
                                                    wire:model="version">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        wire:click="generateVersion">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('version')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="locale">Language</label>
                                    <select class="form-control @error('locale') is-invalid @enderror"
                                        id="locale"
                                        wire:model="locale">
                                        <option value="en">English</option>
                                        <option value="es">Spanish</option>
                                        <option value="fr">French</option>
                                        <option value="de">German</option>
                                    </select>
                                    @error('locale')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group" wire:ignore>
                                    <label for="content">Terms Content *</label>
                                    <div>
                                        <textarea id="content"
                                            class="form-control tinymce @error('content') is-invalid @enderror"
                                            data-model="content"
                                            name="content"></textarea>
                                    </div>
                                    @error('content')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Settings & Actions -->
                    <div class="col-md-4">
                        <!-- Status & Dates -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-clock mr-2"></i>Status & Dates
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                            class="custom-control-input"
                                            id="is_active"
                                            wire:model="is_active">
                                        <label class="custom-control-label" for="is_active">
                                            Publish immediately
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox"
                                            class="custom-control-input"
                                            id="requires_acceptance"
                                            wire:model="requires_acceptance">
                                        <label class="custom-control-label" for="requires_acceptance">
                                            Requires user acceptance
                                        </label>
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label for="effective_date">Effective Date</label>
                                    <input type="datetime-local"
                                        class="form-control @error('effective_date') is-invalid @enderror"
                                        id="effective_date"
                                        wire:model="effective_date">
                                    @error('effective_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-cogs mr-2"></i>Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.terms') }}" class="btn btn-secondary">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            <i class="fas fa-save mr-2"></i>Create
                                        </span>
                                        <span wire:loading>
                                            <i class="fas fa-spinner fa-spin mr-2"></i>Saving...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        .ck-editor__editable {
            min-height: 400px;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>
    @endpush
</div>