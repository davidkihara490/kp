<div>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold d-inline">
                    <i class="fas fa-file-contract mr-2"></i>Edit Terms & Conditions
                </h3>
                <a href="{{ route('admin.terms') }}" class="btn btn-secondary btn-sm float-right">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Terms
                </a>
            </div>

            <div class="card-body">
                @include('components.alerts.response-alerts')

                <form wire:submit.prevent="update">
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

                                    <!-- CKEditor Content -->
                                    <div class="form-group"  wire:ignore>
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
                                                Active
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

                                    @if($created_at)
                                    <div class="form-group">
                                        <label>Created</label>
                                        <p class="form-control-static">{{ $created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    @endif

                                    @if($updated_at)
                                    <div class="form-group">
                                        <label>Last Updated</label>
                                        <p class="form-control-static">{{ $updated_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    @endif

                                    @if($created_by_name)
                                    <div class="form-group">
                                        <label>Created By</label>
                                        <p class="form-control-static">{{ $created_by_name }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Danger Zone -->
                            <div class="card mb-4 border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <button type="button" class="btn btn-danger btn-block" wire:click="confirmDelete">
                                        <i class="fas fa-trash mr-2"></i>Delete Terms
                                    </button>
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
                                                <i class="fas fa-save mr-2"></i>Update
                                            </span>
                                            <span wire:loading>
                                                <i class="fas fa-spinner fa-spin mr-2"></i>Updating...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Delete Confirmation Modal -->
                @if($showDeleteModal)
                <div class="modal fade show" id="deleteModal" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>Confirm Delete
                                </h5>
                                <button type="button" class="close text-white" wire:click="$set('showDeleteModal', false)">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete these terms?</p>
                                <p class="font-weight-bold">"{{ $title }}"</p>
                                <p class="text-danger">This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="$set('showDeleteModal', false)">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </button>
                                <button type="button" class="btn btn-danger" wire:click="delete" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="fas fa-trash mr-2"></i>Delete Permanently
                                    </span>
                                    <span wire:loading>
                                        <i class="fas fa-spinner fa-spin mr-2"></i>Deleting...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
        <script>
            document.addEventListener('livewire:init', function() {
                let editor;

                ClassicEditor
                    .create(document.querySelector('#content'), {
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo'],
                        heading: {
                            options: [{
                                    model: 'paragraph',
                                    title: 'Paragraph',
                                    class: 'ck-heading_paragraph'
                                },
                                {
                                    model: 'heading1',
                                    view: 'h1',
                                    title: 'Heading 1',
                                    class: 'ck-heading_heading1'
                                },
                                {
                                    model: 'heading2',
                                    view: 'h2',
                                    title: 'Heading 2',
                                    class: 'ck-heading_heading2'
                                },
                                {
                                    model: 'heading3',
                                    view: 'h3',
                                    title: 'Heading 3',
                                    class: 'ck-heading_heading3'
                                }
                            ]
                        }
                    })
                    .then(newEditor => {
                        editor = newEditor;
                        editor.model.document.on('change:data', () => {
                            @this.set('content', editor.getData());
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });

                // Listen for content updates from Livewire
                Livewire.on('content-updated', (content) => {
                    if (editor) {
                        editor.setData(content);
                    }
                });
            });
        </script>
        @endpush

        @push('styles')
        <style>
            .ck-editor__editable {
                min-height: 400px;
            }

            .form-control:focus {
                border-color: #80bdff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }

            .modal.show {
                display: block;
            }
        </style>
        @endpush
    </div>
</div>