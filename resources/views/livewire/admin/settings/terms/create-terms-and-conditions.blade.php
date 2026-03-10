<div>
<div>
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

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'content' ? 'active' : '' }}" 
                           wire:click="$set('activeTab', 'content')" 
                           href="#"
                           role="tab">
                            <i class="fas fa-pen mr-2"></i>Content
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'legal' ? 'active' : '' }}" 
                           wire:click="$set('activeTab', 'legal')" 
                           href="#"
                           role="tab">
                            <i class="fas fa-gavel mr-2"></i>Legal Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'seo' ? 'active' : '' }}" 
                           wire:click="$set('activeTab', 'seo')" 
                           href="#"
                           role="tab">
                            <i class="fas fa-search mr-2"></i>SEO Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'preview' ? 'active' : '' }}" 
                           wire:click="$set('activeTab', 'preview')" 
                           href="#"
                           role="tab">
                            <i class="fas fa-eye mr-2"></i>Preview
                        </a>
                    </li>
                </ul>

                <form wire:submit.prevent="save">
                    <div class="row">
                        <!-- Left Column - Main Content -->
                        <div class="col-md-8">
                            @if($activeTab === 'content')
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

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="locale">Language</label>
                                                    <select class="form-control @error('locale') is-invalid @enderror" 
                                                            id="locale" 
                                                            wire:model="locale">
                                                        <option value="en">English</option>
                                                        <option value="es">Spanish</option>
                                                        <option value="fr">French</option>
                                                        <option value="de">German</option>
                                                        <option value="it">Italian</option>
                                                        <option value="pt">Portuguese</option>
                                                        <option value="ja">Japanese</option>
                                                        <option value="zh">Chinese</option>
                                                    </select>
                                                    @error('locale')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Template</label>
                                                    <div class="btn-group d-flex" role="group">
                                                        <button type="button" 
                                                                class="btn btn-outline-primary btn-sm"
                                                                wire:click="loadTemplate('standard')">
                                                            Standard
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-outline-primary btn-sm"
                                                                wire:click="loadTemplate('ecommerce')">
                                                            E-commerce
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-outline-primary btn-sm"
                                                                wire:click="loadTemplate('saas')">
                                                            SaaS
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="content">Terms Content *</label>
                                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                                      id="content" 
                                                      wire:model="content"
                                                      rows="12" 
                                                      placeholder="Enter the full terms and conditions content..."></textarea>
                                            @error('content')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small class="form-text text-muted">
                                                {{ strlen($content) }} characters. Use Markdown for formatting.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($activeTab === 'legal')
                                <!-- Legal Details -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-gavel mr-2"></i>Legal Provisions
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="introduction">Introduction</label>
                                            <textarea class="form-control" 
                                                      id="introduction" 
                                                      wire:model="introduction"
                                                      rows="2" 
                                                      placeholder="Brief introduction to your terms..."></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="definitions">Definitions</label>
                                            <textarea class="form-control" 
                                                      id="definitions" 
                                                      wire:model="definitions"
                                                      rows="3" 
                                                      placeholder="Define key terms used in your document..."></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="user_obligations">User Obligations</label>
                                            <textarea class="form-control" 
                                                      id="user_obligations" 
                                                      wire:model="user_obligations"
                                                      rows="3" 
                                                      placeholder="List user responsibilities and prohibited activities..."></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="liability_limitations">Limitation of Liability</label>
                                            <textarea class="form-control" 
                                                      id="liability_limitations" 
                                                      wire:model="liability_limitations"
                                                      rows="3" 
                                                      placeholder="Specify liability limitations and disclaimers..."></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="governing_law">Governing Law</label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="governing_law"
                                                           wire:model="governing_law"
                                                           placeholder="e.g., United States">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jurisdiction">Jurisdiction</label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="jurisdiction"
                                                           wire:model="jurisdiction"
                                                           placeholder="e.g., New York">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="dispute_resolution">Dispute Resolution</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="dispute_resolution"
                                                   wire:model="dispute_resolution"
                                                   placeholder="e.g., Arbitration, Mediation, Court">
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Information -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-building mr-2"></i>Company Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="company_name">Company Name</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="company_name"
                                                   wire:model="company_name">
                                        </div>

                                        <div class="form-group">
                                            <label for="company_address">Address</label>
                                            <textarea class="form-control" 
                                                      id="company_address" 
                                                      wire:model="company_address"
                                                      rows="2"></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact_email">Contact Email</label>
                                                    <input type="email" 
                                                           class="form-control" 
                                                           id="contact_email"
                                                           wire:model="contact_email">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact_phone">Contact Phone</label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="contact_phone"
                                                           wire:model="contact_phone">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($activeTab === 'seo')
                                <!-- SEO Information -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-search mr-2"></i>SEO Settings
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text"
                                                   class="form-control @error('meta_title') is-invalid @enderror"
                                                   id="meta_title" 
                                                   wire:model="meta_title"
                                                   placeholder="SEO title for search engines">
                                            @error('meta_title')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <div class="d-flex justify-content-between">
                                                <small class="form-text text-muted">
                                                    Recommended: 50-60 characters
                                                </small>
                                                <small class="form-text {{ strlen($meta_title) > 60 ? 'text-danger' : 'text-success' }}">
                                                    {{ strlen($meta_title) }} characters
                                                </small>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                                      id="meta_description"
                                                      wire:model="meta_description" 
                                                      rows="3" 
                                                      placeholder="SEO description for search engines"></textarea>
                                            @error('meta_description')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <div class="d-flex justify-content-between">
                                                <small class="form-text text-muted">
                                                    Recommended: 150-160 characters
                                                </small>
                                                <small class="form-text {{ strlen($meta_description) > 160 ? 'text-danger' : 'text-success' }}">
                                                    {{ strlen($meta_description) }} characters
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($activeTab === 'preview')
                                <!-- Preview -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-eye mr-2"></i>Document Preview
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            $previewContent = $content ?: $this->assembleContent();
                                        @endphp
                                        
                                        @if($previewContent)
                                            <div class="preview-content border p-4 bg-white" style="min-height: 400px;">
                                                <h1 class="h3 mb-4">{{ $title ?: 'Terms and Conditions' }}</h1>
                                                <p class="text-muted">Version: {{ $version }} | Effective: {{ $effective_date ?: 'Not set' }}</p>
                                                <hr>
                                                <div class="markdown-content">
                                                    {!! nl2br(e($previewContent)) !!}
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No content to preview yet. Add content in the Content tab.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
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
                                                Publish immediately (make active)
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

                                    <div class="form-group">
                                        <label for="expiry_date">Expiry Date (Optional)</label>
                                        <input type="datetime-local" 
                                               class="form-control @error('expiry_date') is-invalid @enderror" 
                                               id="expiry_date"
                                               wire:model="expiry_date">
                                        @error('expiry_date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Document Preview Card -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle mr-2"></i>Document Info
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Title:</strong>
                                        <div class="mt-1">
                                            <span class="badge badge-primary p-2">
                                                {{ $title ?: 'Untitled Document' }}
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
                                        <strong>Language:</strong>
                                        <div class="mt-1">
                                            <span class="badge badge-secondary">{{ strtoupper($locale) }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Status:</strong>
                                        <div class="mt-1">
                                            @if($is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-warning">Draft</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Acceptance Required:</strong>
                                        <div class="mt-1">
                                            @if($requires_acceptance)
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-secondary">No</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($effective_date)
                                        <div class="mb-3">
                                            <strong>Effective:</strong>
                                            <div class="mt-1">
                                                <span class="badge badge-info">
                                                    {{ \Carbon\Carbon::parse($effective_date)->format('M d, Y H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
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
                                    <button type="button" class="btn btn-info btn-block mb-2" wire:click="togglePreview">
                                        <i class="fas fa-eye mr-2"></i>{{ $showPreview ? 'Hide' : 'Show' }} Preview
                                    </button>

                                    <div class="d-flex justify-content-between mt-3">
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

                                    <hr>

                                    <div class="small text-muted">
                                        <p><strong>Legal Tips:</strong></p>
                                        <ul class="pl-3">
                                            <li>Use clear, plain language</li>
                                            <li>Define all key terms</li>
                                            <li>Consult with legal counsel</li>
                                            <li>Keep version history</li>
                                            <li>Update regularly for compliance</li>
                                        </ul>
                                    </div>
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
            .nav-tabs .nav-link {
                color: #495057;
                font-weight: 500;
            }
            .nav-tabs .nav-link.active {
                color: #007bff;
                border-bottom: 2px solid #007bff;
            }
            .preview-content {
                border-radius: 4px;
                line-height: 1.6;
            }
            .markdown-content h1,
            .markdown-content h2,
            .markdown-content h3 {
                margin-top: 1.5rem;
                margin-bottom: 1rem;
            }
            .markdown-content ul,
            .markdown-content ol {
                padding-left: 1.5rem;
            }
            .badge {
                transition: all 0.3s ease;
            }
            .form-control:focus {
                border-color: #80bdff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }
        </style>
    @endpush
</div></div>
