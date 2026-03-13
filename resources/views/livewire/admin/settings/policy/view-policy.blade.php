<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                <i class="fas fa-file-alt mr-2"></i>View Policy
            </h3>
            <div class="float-right">
                <a href="{{ route('admin.policy.edit', $policyId) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('admin.policy') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Policies
                </a>
            </div>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <!-- Status Bar -->
            <div class="alert mb-4 {{ $is_active ? 'alert-success' : 'alert-warning' }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas {{ $is_active ? 'fa-check-circle' : 'fa-clock' }} mr-2"></i>
                        <strong>Status:</strong> 
                        @if($is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-warning">Inactive</span>
                        @endif
                    </div>
                    
                    @if($published_on)
                        <div>
                            <i class="fas fa-calendar-alt mr-1"></i>
                            <strong>Published:</strong> {{ $published_on->format('F d, Y') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- Main Content -->
                <div class="col-md-8">
                    <!-- Document Header -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h1 class="h2 mb-3">{{ $title }}</h1>
                            
                            <div class="row text-muted mb-3">
                                <div class="col-md-6">
                                    <i class="fas fa-tag mr-1"></i> Version: <strong>{{ $version }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <i class="fas fa-clock mr-1"></i> Last Updated: 
                                    <strong>{{ $updated_at ? $updated_at->format('M d, Y') : 'N/A' }}</strong>
                                </div>
                            </div>

                            <hr>

                            <!-- Document Content -->
                            <div class="document-content mt-4">
                                {!! $content !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Metadata Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle mr-2"></i>Document Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><strong>Policy ID:</strong></td>
                                    <td><code>#{{ $policyId }}</code></td>
                                </tr>
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
                                @if($updated_by_name)
                                <tr>
                                    <td><strong>Last Updated By:</strong></td>
                                    <td>
                                        <span class="badge badge-light">
                                            <i class="fas fa-user-edit mr-1"></i>{{ $updated_by_name }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Published Date Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-calendar-alt mr-2"></i>Publication Date
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted mb-1">Published On</label>
                                <div class="h5">
                                    @if($published_on)
                                        {{ $published_on->format('F d, Y') }}
                                        <small class="text-muted">({{ $published_on->diffForHumans() }})</small>
                                    @else
                                        <span class="text-muted">Not published yet</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-bar mr-2"></i>Statistics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="h3 mb-0">{{ strlen($content) }}</div>
                                <small class="text-muted">Total Characters</small>
                            </div>
                            <div class="text-center">
                                <div class="h3 mb-0">{{ str_word_count(strip_tags($content)) }}</div>
                                <small class="text-muted">Approx. Word Count</small>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cogs mr-2"></i>Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.policy.edit', $policyId) }}" class="btn btn-primary btn-block mb-2">
                                    <i class="fas fa-edit mr-2"></i>Edit Policy
                                </a>
                                
                                <button type="button" class="btn btn-info btn-block mb-2" onclick="window.print()">
                                    <i class="fas fa-print mr-2"></i>Print / PDF
                                </button>
                                
                                <button type="button" class="btn btn-outline-secondary btn-block" 
                                        onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied to clipboard!');">
                                    <i class="fas fa-copy mr-2"></i>Copy Link
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .document-content {
            line-height: 1.8;
            font-size: 1rem;
        }
        
        .document-content h1 {
            font-size: 2rem;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .document-content h2 {
            font-size: 1.5rem;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .document-content h3 {
            font-size: 1.25rem;
            margin-top: 1.25rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }
        
        .document-content p {
            margin-bottom: 1rem;
        }
        
        .document-content ul,
        .document-content ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }
        
        .document-content li {
            margin-bottom: 0.25rem;
        }
        
        .document-content blockquote {
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #007bff;
            background-color: #f8f9fa;
        }
        
        .document-content table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }
        
        .document-content th,
        .document-content td {
            padding: 0.5rem;
            border: 1px solid #dee2e6;
        }
        
        .document-content th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        /* Print styles */
        @media print {
            .btn, .card-header, .sidebar, footer, nav {
                display: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .document-content {
                font-size: 12pt;
            }
        }
    </style>
    @endpush
</div>