@extends('layouts.app')

@section('title', 'Delivery Tariff | Karibu Parcels')

@section('meta')
    <meta name="description" content="View our nationwide delivery pricing for 0-5kg base rate and additional per kg charges across all delivery zones in Kenya.">
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="tariff-hero">
        <div class="container text-center">
            <h1><i class="bi bi-table me-2"></i> Delivery Tariff</h1>
            <p>Nationwide delivery pricing for 0-5kg base rate and additional per kg charges across delivery zones</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <!-- Stats Cards -->
        <div class="row mb-3">
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $zones->count() }}</div>
                    <div class="stats-label">Delivery Zones</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $pricingItems->count() }}</div>
                    <div class="stats-label">Pricing Routes</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-number">0-5 kg</div>
                    <div class="stats-label">Base Weight</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-number">40-90</div>
                    <div class="stats-label">Extra/kg (KSh)</div>
                </div>
            </div>
        </div>

        <!-- Tariff Table -->
        <div class="tariff-card" id="tariffContent">
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                <h5 class="mb-1"><i class="fas fa-route text-primary me-2"></i> Delivery Pricing Tariffs</h5>
                <div class="d-flex gap-2">
                    <span class="badge bg-success" style="font-size: 0.65rem;"><i class="fas fa-weight-hanging me-1"></i> 0-5kg: Base</span>
                    <span class="badge bg-warning text-dark" style="font-size: 0.65rem;"><i class="fas fa-plus-circle me-1"></i> Extra/kg</span>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="tariff-table" id="pricingTable">
                    <thead>
                        <tr>
                            <th style="background: var(--primary-dark);">
                                <div>FROM / TO</div>
                                <div class="small">Base / Extra</div>
                            </th>
                            @foreach($zones as $destination)
                            <th class="text-center">
                                <div class="zone-name">{{ $destination->name }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($zones as $source)
                        <tr>
                            <td class="source-cell">
                                <i class="fas fa-flag-checkered text-primary me-1"></i>
                                {{ $source->name }}
                            </td>
                            @foreach($zones as $destination)
                            @php
                            $pricing = $pricingItems->where('source_zone_id', $source->id)
                                ->where('destination_zone_id', $destination->id)
                                ->first();
                            @endphp
                            <td class="price-cell text-center">
                                @if($pricing)
                                <div>
                                    <span class="price-badge base-price">
                                        KSh {{ number_format($pricing->cost) }}
                                    </span>
                                </div>
                                <div class="mt-1">
                                    <span class="price-badge extra-price">
                                        +{{ number_format($pricing->extra) }}/kg
                                    </span>
                                </div>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="alert alert-info mt-2 mb-0" style="font-size: 0.7rem; padding: 8px 12px;">
                <i class="fas fa-info-circle me-1"></i>
                <strong>Guide:</strong> Base price for 0-5kg | Extra charge per kg beyond 5kg.
                <small class="text-muted">* Prices in KSh, subject to change.</small>
            </div>

            <div class="alert alert-info mt-2 mb-0" style="font-size: 0.7rem; padding: 8px 12px;">
                <i class="fas fa-info-circle me-1"></i>
                <strong>Note:</strong> All prices are subject to 16% VAT.
            </div>
        </div>

        <!-- Zones and Towns Table -->
        <div class="zones-section" id="zonesContent">
            <h5 class="mb-3"><i class="fas fa-map-marked-alt text-primary me-2"></i> Zones and Covered Towns</h5>
            <div class="table-wrapper">
                <table class="zones-table">
                    <thead>
                        <tr>
                            <th>Zone Name</th>
                            <th>Towns / Locations Covered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($zones as $zone)
                        <tr>
                            <td class="zone-name-cell">
                                <i class="fas fa-location-dot text-primary me-1"></i>
                                {{ $zone->name }}
                            </td>
                            <td class="towns-cell">
                                @php
                                $townNames = $zone->towns->pluck('town.name')->filter()->toArray();
                                @endphp
                                @if(count($townNames) > 0)
                                {{ implode(', ', $townNames) }}
                                @else
                                <span class="text-muted">Main city center, Surrounding areas</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="alert alert-secondary mt-2" style="font-size: 0.7rem; padding: 8px 12px;">
                <i class="fas fa-info-circle me-1"></i>
                <strong>Service Coverage:</strong> We deliver to all major towns and cities within each zone.
            </div>
        </div>

        <!-- Download Button -->
        <div class="download-section text-center">
            <a target="_blank" href="{{ route('pricing.download') }}" class="btn btn-success btn-download-small">
                <i class="fas fa-file-pdf me-2"></i> Download Tariff PDF
            </a>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .tariff-hero {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        padding: 40px 0;
        color: white;
        text-align: center;
        margin-bottom: 30px;
    }
    .tariff-hero h1 {
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 10px;
    }
    .tariff-hero p {
        font-size: 1rem;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
    }
    .tariff-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        padding: 20px;
        margin-bottom: 30px;
        overflow-x: auto;
    }
    .tariff-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.75rem;
    }
    .tariff-table th {
        background: var(--primary-color);
        color: white;
        padding: 8px 6px;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        border: 1px solid rgba(255,255,255,0.2);
        font-size: 0.75rem;
    }
    .tariff-table td {
        padding: 6px 4px;
        text-align: center;
        vertical-align: middle;
        border: 1px solid var(--border-color);
        font-size: 0.75rem;
    }
    .tariff-table tr:hover td {
        background-color: var(--primary-light);
    }
    .source-cell {
        background-color: #f8f9fa;
        font-weight: 700;
        color: var(--primary-color);
        position: sticky;
        left: 0;
        background-color: white;
        border-right: 2px solid var(--primary-color);
        white-space: nowrap;
        font-size: 0.75rem;
    }
    .price-badge {
        display: inline-block;
        padding: 3px 6px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.7rem;
    }
    .base-price {
        background-color: var(--primary-light);
        color: var(--primary-color);
    }
    .extra-price {
        background-color: #fff3e0;
        color: #ff9800;
        font-size: 0.65rem;
    }
    .price-cell {
        padding: 6px 4px !important;
    }
    .zone-name {
        font-weight: 600;
        font-size: 0.7rem;
        white-space: nowrap;
    }
    .table-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin-bottom: 15px;
    }
    .zones-section {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        padding: 20px;
        margin-bottom: 30px;
    }
    .zones-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.75rem;
    }
    .zones-table th {
        background: var(--primary-dark);
        color: white;
        padding: 8px 12px;
        font-weight: 600;
        text-align: left;
        font-size: 0.75rem;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .zones-table td {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        vertical-align: top;
        font-size: 0.75rem;
    }
    .zones-table tr:hover td {
        background-color: var(--primary-light);
    }
    .zone-name-cell {
        font-weight: 700;
        color: var(--primary-color);
        width: 25%;
        background-color: #f8f9fa;
    }
    .towns-cell {
        color: var(--text-light);
        line-height: 1.5;
    }
    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        border: 1px solid var(--border-color);
        margin-bottom: 20px;
    }
    .stats-number {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary-color);
    }
    .stats-label {
        font-size: 0.7rem;
        color: var(--text-light);
        margin-top: 5px;
    }
    .download-section {
        text-align: center;
        margin-bottom: 30px;
    }
    .btn-download-small {
        background: #dc3545;
        color: white;
        border-radius: 8px;
        padding: 8px 20px;
        font-size: 0.85rem;
        font-weight: 500;
        border: none;
        transition: var(--transition);
    }
    .btn-download-small:hover {
        background: #c82333;
        color: white;
        transform: translateY(-2px);
    }
    @media (max-width: 768px) {
        .tariff-hero h1 { font-size: 1.8rem; }
        .tariff-card { padding: 10px; }
        .tariff-table { font-size: 0.65rem; }
        .tariff-table th, .tariff-table td { padding: 4px 2px; }
        .zone-name { font-size: 0.6rem; }
        .zones-table th, .zones-table td { padding: 6px 8px; font-size: 0.7rem; }
    }
    @media print {
        body { padding-top: 0; background: white; }
        .navbar, .tariff-hero, footer, .download-section, .business-solutions { display: none !important; }
        .tariff-card, .zones-section { box-shadow: none; padding: 0; margin: 0; page-break-inside: avoid; }
        .container { max-width: 100%; padding: 0; margin: 0; }
    }
</style>
@endpush