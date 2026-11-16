<div>
    <div>
        <div class="rates-container">
            <div class="container py-4">
                <!-- Header -->
                <div class="dashboard-header mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <a href="{{ route('partners.rates.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                                <i class="bi bi-arrow-left me-1"></i> Back to Items
                            </a>
                            <h2 class="fw-bold mb-1">
                                <i class="bi bi-pencil-square me-2 text-primary"></i>
                                Edit Rates
                            </h2>
                            <p class="text-muted mb-0">
                                Item: <strong>{{ $item->name }}</strong>
                                @if($item->code)
                                <span class="item-code-inline">{{ $item->code }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <button class="btn btn-outline-secondary rounded-pill me-2" wire:click="clearAllRates"
                                wire:confirm="Are you sure you want to clear all rate values? You still need to click Save to persist.">
                                <i class="bi bi-eraser me-1"></i> Clear
                            </button>
                            <button class="btn btn-primary rounded-pill px-4" wire:click="save" wire:loading.attr="disabled">
                                <span wire:loading.remove><i class="bi bi-save me-1"></i> Save Rates</span>
                                <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session()->has('info'))
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Info Banner -->
                <div class="info-banner mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    Enter a cost for each source → destination zone combination. Leave empty to not charge for that route.
                    <strong>Click "Save Rates" when done.</strong>
                </div>

                <!-- Excel Bulk Upload -->
                <div class="excel-upload-card mb-4">
                    <div class="excel-upload-header">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-file-earmark-excel me-2"></i> Bulk Upload via Excel
                        </h6>
                    </div>
                    <div class="excel-upload-body">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Download Template</label>
                                <button type="button"
                                        class="btn btn-success w-100"
                                        wire:click="downloadTemplate"
                                        wire:loading.attr="disabled"
                                        wire:target="downloadTemplate">
                                    <span wire:loading.remove wire:target="downloadTemplate">
                                        <i class="bi bi-download me-1"></i> Download Excel Template
                                    </span>
                                    <span wire:loading wire:target="downloadTemplate">
                                        <span class="spinner-border spinner-border-sm me-1"></span> Generating...
                                    </span>
                                </button>
                                <small class="text-muted d-block mt-1">
                                    Pre-filled with all zone pairs for <strong>{{ $item->name }}</strong>.
                                </small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Upload Filled Excel</label>
                                <input type="file"
                                       class="form-control"
                                       wire:model="excel_file"
                                       accept=".xlsx,.xls,.csv">
                                @error('excel_file')
                                <span class="text-danger d-block small mt-1">{{ $message }}</span>
                                @enderror
                                <div wire:loading wire:target="excel_file" class="text-info small mt-1">
                                    <span class="spinner-border spinner-border-sm"></span> Uploading...
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <button type="button"
                                        class="btn btn-primary w-100"
                                        wire:click="importExcel"
                                        wire:loading.attr="disabled"
                                        wire:target="importExcel,excel_file"
                                        @if(!$excel_file) disabled @endif>
                                    <span wire:loading.remove wire:target="importExcel">
                                        <i class="bi bi-upload me-1"></i> Import Excel
                                    </span>
                                    <span wire:loading wire:target="importExcel">
                                        <span class="spinner-border spinner-border-sm me-1"></span> Importing...
                                    </span>
                                </button>
                            </div>
                        </div>

                        @if(!empty($import_errors))
                        <div class="alert alert-warning mt-3 mb-0">
                            <strong>Import Warnings:</strong>
                            <ul class="mb-0 ps-3">
                                @foreach($import_errors as $err)
                                <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <small class="text-muted d-block mt-3">
                            Columns: <code>item_id</code>, <code>source_zone_id</code>, <code>source_zone_name</code>,
                            <code>destination_zone_id</code>, <code>destination_zone_name</code>, <code>cost</code>, <code>extra</code>.
                            Fill in <strong>cost</strong> then upload. Imported values <strong>overwrite</strong> matching cells in the grid;
                            other cells are left untouched. You must click <strong>Save Rates</strong> to persist.
                        </small>
                    </div>
                </div>

                <!-- Bulk Apply Tool -->
                <div class="bulk-tool mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Source Zone (optional)</label>
                            <select class="form-select" wire:model="bulkSourceZoneId">
                                <option value="">All sources</option>
                                @foreach($sourceZones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Destination Zone (optional)</label>
                            <select class="form-select" wire:model="bulkDestinationZoneId">
                                <option value="">All destinations</option>
                                @foreach($destinationZones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cost (KES)</label>
                            <input type="number" class="form-control" wire:model="bulkCost" placeholder="0.00" min="0" step="0.01">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success w-100" wire:click="applyBulkCost">
                                <i class="bi bi-lightning-fill me-1"></i> Apply to All Selected
                            </button>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-lightbulb me-1"></i>
                        Leave both dropdowns empty and enter a cost to apply it to <strong>all combinations</strong>.
                    </small>
                </div>

                <!-- Zones Grid -->
                @if($sourceZones->count() > 0 && $destinationZones->count() > 0)
                <div class="table-responsive">
                    <table class="rates-grid">
                        <thead>
                            <tr>
                                <th class="corner-cell">
                                    <i class="bi bi-arrow-down-up"></i>
                                    <span>From ↓ / To →</span>
                                </th>
                                @foreach($destinationZones as $destZone)
                                <th class="dest-header">{{ $destZone->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sourceZones as $sourceZone)
                            <tr>
                                <th class="source-header">{{ $sourceZone->name }}</th>
                                @foreach($destinationZones as $destZone)
                                <td class="rate-cell">
                                    <div class="rate-input-wrapper">
                                        <span class="currency-prefix">KES</span>
                                        <input type="number"
                                            class="rate-input"
                                            placeholder="0"
                                            min="0"
                                            step="0.01"
                                            wire:model.lazy="rates.{{ $sourceZone->id }}.{{ $destZone->id }}.cost"
                                            wire:change="setRate({{ $sourceZone->id }}, {{ $destZone->id }}, $event.target.value)">
                                    </div>
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Save Footer -->
                <div class="save-footer">
                    <button class="btn btn-secondary" wire:click="deleteAllRates"
                        wire:confirm="Are you sure you want to delete ALL rates for this item? This cannot be undone.">
                        <i class="bi bi-trash me-1"></i> Delete All Rates
                    </button>
                    <button class="btn btn-primary btn-lg px-5" wire:click="save" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="bi bi-save me-1"></i> Save All Rates</span>
                        <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
                    </button>
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    No active zones available. Please create zones first.
                </div>
                @endif
            </div>

            <style>
                .rates-container {
                    background: var(--light-bg);
                    min-height: 100vh;
                    padding: 40px 0 60px;
                }

                .item-code-inline {
                    font-family: monospace;
                    background: #e9ecef;
                    padding: 2px 8px;
                    border-radius: 4px;
                    font-size: 0.8rem;
                    color: #495057;
                }

                .info-banner {
                    background: #e8f5e9;
                    border-left: 4px solid #008f40;
                    padding: 12px 18px;
                    border-radius: 8px;
                    font-size: 0.9rem;
                    color: #155724;
                }

                /* Excel upload card */
                .excel-upload-card {
                    background: white;
                    border-radius: 16px;
                    overflow: hidden;
                    border: 1px solid #e9ecef;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                }

                .excel-upload-header {
                    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
                    padding: 14px 20px;
                    border-bottom: 1px solid #e9ecef;
                    color: #1565c0;
                }

                .excel-upload-body {
                    padding: 20px;
                }

                .excel-upload-body .form-label {
                    font-weight: 600;
                    font-size: 0.85rem;
                    color: #495057;
                    margin-bottom: 4px;
                }

                .bulk-tool {
                    background: white;
                    border-radius: 16px;
                    padding: 20px;
                    border: 1px solid #e9ecef;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                }

                .bulk-tool .form-label {
                    font-weight: 600;
                    font-size: 0.85rem;
                    color: #495057;
                    margin-bottom: 4px;
                }

                .bulk-tool .form-select,
                .bulk-tool .form-control {
                    border-radius: 10px;
                    border: 2px solid #e9ecef;
                }

                .rates-grid {
                    background: white;
                    border-radius: 16px;
                    overflow: hidden;
                    border-collapse: separate;
                    border-spacing: 0;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                    border: 1px solid #e9ecef;
                    width: 100%;
                    min-width: 600px;
                }

                .rates-grid thead th {
                    background: #008f40;
                    color: white;
                    padding: 14px 12px;
                    font-weight: 600;
                    font-size: 0.85rem;
                    text-align: center;
                    border-right: 1px solid rgba(255, 255, 255, 0.15);
                    white-space: nowrap;
                }

                .rates-grid thead th:last-child {
                    border-right: none;
                }

                .rates-grid .corner-cell {
                    background: #007a36;
                    text-align: left;
                    font-size: 0.75rem;
                    min-width: 140px;
                }

                .rates-grid .corner-cell i {
                    margin-right: 4px;
                }

                .source-header {
                    background: #f8f9fa;
                    padding: 14px 12px;
                    font-weight: 600;
                    font-size: 0.85rem;
                    text-align: left;
                    color: #1e293b;
                    border-bottom: 1px solid #e9ecef;
                    border-right: 2px solid #e9ecef;
                    position: sticky;
                    left: 0;
                    z-index: 1;
                }

                .rate-cell {
                    padding: 6px;
                    border-bottom: 1px solid #e9ecef;
                    border-right: 1px solid #f1f5f9;
                    text-align: center;
                }

                .rate-cell:last-child {
                    border-right: none;
                }

                .rate-input-wrapper {
                    position: relative;
                    display: flex;
                    align-items: center;
                }

                .currency-prefix {
                    position: absolute;
                    left: 8px;
                    font-size: 0.7rem;
                    color: #6c757d;
                    font-weight: 600;
                    pointer-events: none;
                }

                .rate-input {
                    width: 100%;
                    min-width: 80px;
                    padding: 8px 8px 8px 32px;
                    border: 1.5px solid #e9ecef;
                    border-radius: 8px;
                    font-size: 0.85rem;
                    text-align: right;
                    transition: all 0.15s;
                    background: white;
                }

                .rate-input:focus {
                    outline: none;
                    border-color: #008f40;
                    box-shadow: 0 0 0 3px rgba(0, 143, 64, 0.1);
                    background: #f8fff9;
                }

                .rate-input::placeholder {
                    color: #cbd5e1;
                }

                .save-footer {
                    background: white;
                    border-radius: 16px;
                    padding: 20px;
                    margin-top: 24px;
                    border: 1px solid #e9ecef;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 12px;
                }

                @media (max-width: 768px) {
                    .rates-container {
                        padding: 20px 0 40px;
                    }

                    .save-footer {
                        flex-direction: column;
                    }

                    .save-footer .btn {
                        width: 100%;
                    }
                }
            </style>
        </div>
    </div>
</div>