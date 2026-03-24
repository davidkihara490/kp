<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                <i class="fas fa-file-alt mr-2"></i>Edit Policy
            </h3>
            <div class="float-right">
                <a href="{{ route('admin.policy.view', $policyId) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-eye mr-2"></i>View
                </a>
                <a href="{{ route('admin.policy') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Policies
                </a>
            </div>
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
                                    <i class="fas fa-info-circle mr-2"></i>Policy Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="title">Policy Title *</label>
                                            <input type="text" 
                                                   class="form-control @error('title') is-invalid @enderror"
                                                   id="title" 
                                                   wire:model="title"
                                                   placeholder="e.g., Privacy Policy, Cookie Policy, Refund Policy">
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
                                    <label for="published_on">Published Date</label>
                                    <input type="date" 
                                           class="form-control @error('published_on') is-invalid @enderror"
                                           id="published_on" 
                                           wire:model="published_on">
                                    @error('published_on')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- CKEditor Content -->
                                <div class="form-group" wire:ignore>
                                    <label for="content">Policy Content *</label>
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
                        <!-- Status Card -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-toggle-on mr-2"></i>Status
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
                                            <strong>Active</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Metadata Card -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-history mr-2"></i>Metadata
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ $created_at ? $created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created By:</strong></td>
                                        <td>
                                            <span class="badge badge-light">
                                                <i class="fas fa-user-circle mr-1"></i>{{ $created_by_name }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Updated:</strong></td>
                                        <td>{{ $updated_at ? $updated_at->format('M d, Y H:i') : 'N/A' }}</td>
                                    </tr>
                                </table>
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
                                    <i class="fas fa-trash mr-2"></i>Delete Policy
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
                                    <a href="{{ route('admin.policy') }}" class="btn btn-secondary">
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
                            <p>Are you sure you want to delete this policy?</p>
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
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                        ]
                    }
                })
                .then(newEditor => {
                    editor = newEditor;
                    editor.model.document.on('change:data', () => {
                        @this.set('content', editor.getData());
                    });

                    // Set initial content
                    editor.setData(@js($content));
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
        .modal.show {
            display: block;
        }
    </style>
    @endpush
</div>