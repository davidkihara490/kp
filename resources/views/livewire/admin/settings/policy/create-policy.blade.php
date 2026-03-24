<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                <i class="fas fa-file-alt mr-2"></i>Create Policy
            </h3>
            <a href="{{ route('admin.policy') }}" class="btn btn-secondary btn-sm float-right">
                <i class="fas fa-arrow-left mr-2"></i>Back to Policies
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
                                            <small class="form-text text-muted">
                                                Choose a clear, descriptive title for your policy.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="version">Version *</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control @error('version') is-invalid @enderror" 
                                                       id="version"
                                                       wire:model="version"
                                                       placeholder="v1.0.0">
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
                                    <small class="form-text text-muted">
                                        Leave empty to use current date.
                                    </small>
                                </div>

                                <!-- CKEditor Content -->
                                <div class="form-group" wire:ignore>
                                    <label for="content">Policy Content *</label>
                                    <div>
                                        <textarea id="content" 
                                                  class="form-control tinymce @error('content') is-invalid @enderror"
                                                  data-model="content"
                                                  name="content"
                                                  placeholder="Enter the full policy content..."></textarea>
                                    </div>
                                    @error('content')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Use the editor to format your policy with headings, lists, and emphasis.
                                    </small>
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
                                            <strong>Publish immediately</strong>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        If enabled, this policy will be visible to users.
                                    </small>
                                </div>

                                <hr>

                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <small>
                                        Policies are typically published on your website's legal pages 
                                        (e.g., /privacy-policy, /terms-of-service).
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Card -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-eye mr-2"></i>Quick Preview
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Title:</strong>
                                    <div class="mt-1">
                                        <span class="badge badge-primary p-2">
                                            {{ $title ?: 'Untitled Policy' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <strong>Version:</strong>
                                    <div class="mt-1">
                                        <code>{{ $version ?: 'v1.0.0' }}</code>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <strong>Published:</strong>
                                    <div class="mt-1">
                                        @if($published_on)
                                            <span class="badge badge-info">
                                                {{ \Carbon\Carbon::parse($published_on)->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">Today</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <strong>Content Length:</strong>
                                    <div class="mt-1">
                                        <span class="badge badge-light">
                                            {{ strlen($content) }} characters
                                        </span>
                                    </div>
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
                                    <a href="{{ route('admin.policy') }}" class="btn btn-secondary">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            <i class="fas fa-save mr-2"></i>Create Policy
                                        </span>
                                        <span wire:loading>
                                            <i class="fas fa-spinner fa-spin mr-2"></i>Creating...
                                        </span>
                                    </button>
                                </div>

                                <hr>

                                <div class="small text-muted">
                                    <p class="mb-1"><strong>Tips:</strong></p>
                                    <ul class="pl-3 mb-0">
                                        <li>Use clear, simple language</li>
                                        <li>Be transparent about data handling</li>
                                        <li>Include effective date</li>
                                        <li>Keep it up to date</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                })
                .catch(error => {
                    console.error(error);
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
    </style>
    @endpush
</div>