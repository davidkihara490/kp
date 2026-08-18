@extends('layouts.app')

@section('title', 'PickUp & DropOff Points | Karibu Parcels')

@section('meta')
<meta name="description" content="Find our conveniently located pickup and drop-off points across Kenya. Browse by county to find the nearest Karibu Parcels station.">
@endsection

@section('content')
<!-- Hero Section -->
<section class="stations-hero">
    <div class="container text-center">
        <h1><i class="bi bi-geo-alt-fill me-2"></i> PickUp & DropOff Points</h1>
        <p>Find our conveniently located stations across Kenya — click any county to explore pickup and drop-off locations</p>
        <div class="stats-badge">
            <i class="bi bi-building me-2"></i> {{ $counties->count() }} Counties • {{ $totalPoints ?? $counties->sum('points_count') }} Service Points • Nationwide Coverage
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="stations-main">
    <!-- Filter Bar with Search -->
    <div class="filter-bar">
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" id="countySearch" class="search-input-custom" placeholder="Search county by name...">
        </div>
        <div class="stats-info" id="statsInfo">
            <i class="bi bi-info-circle-fill me-1"></i> <span id="visibleCount">{{ $counties->count() }}</span> counties displayed
        </div>
    </div>

    <!-- Counties Accordion Container -->
    <div id="countiesContainer" class="counties-accordion">
        @if($counties->count() > 0)
        @foreach($counties as $county)
        <div class="county-accordion-card" data-county-name="{{ strtolower($county->name) }}">
            <div class="county-header" data-target="county-{{ $county->id }}">
                <div class="county-title">
                    <div class="county-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <h4 class="county-name">{{ $county->name }} County</h4>
                    <span class="points-count-badge">{{ $county->points_count ?? $county->pickup_points->count() }} Point{{ ($county->points_count ?? $county->pickup_points->count()) != 1 ? 's' : '' }}</span>
                </div>
                <i class="bi bi-chevron-right chevron-icon"></i>
            </div>
            <div class="points-panel" id="county-{{ $county->id }}">
                <div class="stations-grid">
                    @forelse($county->pickup_points as $point)
                    <div class="station-card">
                        <div class="station-card-header">
                            <h5>{{ $point->town->name }}</h5>
                        </div>
                        <div class="station-card-body">
                            <div class="station-detail">
                                <i class="bi bi-house"></i>
                                <span><strong>Name:</strong> {{ $point->name }}</span>
                            </div>
                            <div class="station-detail">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span><strong>Address:</strong> {{ $point->address }}</span>
                            </div>
                            <div class="station-detail">
                                <i class="bi bi-telephone-fill"></i>
                                <span><strong>Phone:</strong> {{ $point->contact_phone_number }}</span>
                            </div>
                            @if ($point->capacity)
                            <div class="station-detail">
                                <i class="bi bi-boxes"></i>
                                <span><strong>Capacity:</strong> {{ $point->capacity }}</span>
                            </div>
                            @endif
                            <div class="hours-badge">
                                <i class="bi bi-clock"></i>
                                {{ \Carbon\Carbon::today()->setTime(8, 0)->format('h:i A') }} - {{ \Carbon\Carbon::today()->setTime(17, 0)->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="bi bi-info-circle fs-1"></i>
                        <p class="mt-2">No pickup points available in this county yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="empty-state">
            <i class="bi bi-geo-alt fs-1"></i>
            <h5 class="mt-3">No counties found</h5>
            <p class="text-muted">No service points are currently available.</p>
        </div>
        @endif
    </div>

    <!-- Back Button -->
    <div class="view-all-wrapper">
        <a href="{{ url('/') }}" class="btn-view-all">
            <i class="bi bi-arrow-left me-2"></i> Back to Home
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stations-hero {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        padding: 60px 0;
        color: white;
        text-align: center;
        margin-bottom: 50px;
    }

    .stations-hero h1 {
        font-weight: 800;
        font-size: 2.8rem;
        margin-bottom: 15px;
    }

    .stations-hero p {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
    }

    .stats-badge {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 40px;
        padding: 8px 20px;
        display: inline-block;
        margin-top: 20px;
        font-weight: 500;
    }

    .stations-main {
        max-width: 1600px;
        margin: 0 auto;
        padding: 0 1.5rem 3rem;
    }

    .filter-bar {
        background: white;
        border-radius: 60px;
        padding: 6px;
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }

    .search-wrapper {
        flex: 1;
        min-width: 250px;
        position: relative;
    }

    .search-wrapper i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
    }

    .search-input-custom {
        width: 100%;
        padding: 14px 20px 14px 48px;
        border: none;
        border-radius: 50px;
        background: var(--light-bg);
        font-size: 0.95rem;
        outline: none;
        transition: var(--transition);
    }

    .search-input-custom:focus {
        background: white;
        box-shadow: 0 0 0 2px var(--primary-light);
    }

    .stats-info {
        padding: 0 20px;
        color: var(--text-light);
        font-weight: 500;
        font-size: 0.9rem;
    }

    .county-accordion-card {
        background: white;
        border-radius: 24px;
        margin-bottom: 1.25rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        transition: var(--transition);
        overflow: hidden;
    }

    .county-accordion-card:hover {
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-color);
    }

    .county-header {
        background: white;
        padding: 1.2rem 1.8rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s;
    }

    .county-header:hover {
        background: var(--primary-light);
    }

    .county-header.active {
        background: linear-gradient(135deg, var(--primary-light), white);
        border-bottom: 1px solid var(--border-color);
    }

    .county-title {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .county-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary-light), #d4f0e0);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--primary-color);
    }

    .county-name {
        font-weight: 700;
        font-size: 1.35rem;
        margin: 0;
        color: var(--text-dark);
    }

    .points-count-badge {
        background: var(--primary-color);
        color: white;
        padding: 6px 16px;
        border-radius: 40px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .chevron-icon {
        font-size: 1.3rem;
        color: var(--text-light);
        transition: transform 0.3s ease;
    }

    .county-header.active .chevron-icon {
        transform: rotate(90deg);
        color: var(--primary-color);
    }

    .points-panel {
        display: none;
        padding: 1.5rem 1.8rem;
        background: #fefefe;
        border-top: 1px solid var(--border-color);
    }

    .points-panel.show {
        display: block;
        animation: fadeSlide 0.3s ease-out;
    }

    @keyframes fadeSlide {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .station-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        border: 1px solid var(--border-color);
        height: 100%;
    }

    .station-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: var(--primary-color);
    }

    .station-card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        padding: 1rem 1.2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .station-card-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: white;
    }

    .station-card-body {
        padding: 1.2rem;
    }

    .station-detail {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 0.85rem;
        color: var(--text-light);
        line-height: 1.4;
    }

    .station-detail i {
        width: 20px;
        color: var(--primary-color);
        margin-top: 2px;
        font-size: 0.9rem;
    }

    .station-detail strong {
        color: var(--text-dark);
        font-weight: 600;
    }

    .hours-badge {
        background: var(--primary-light);
        border-radius: 30px;
        padding: 8px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--primary-dark);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 24px;
        color: var(--text-light);
    }

    .view-all-wrapper {
        text-align: center;
        margin-top: 2.5rem;
    }

    .btn-view-all {
        background: white;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        padding: 12px 32px;
        border-radius: 50px;
        font-weight: 600;
        transition: var(--transition);
        text-decoration: none;
        display: inline-block;
    }

    .btn-view-all:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 143, 64, 0.2);
    }

    @media (max-width: 768px) {
        .stations-hero h1 {
            font-size: 2rem;
        }

        .stations-main {
            padding: 0 1rem 2rem;
        }

        .county-header {
            padding: 1rem 1.2rem;
            flex-wrap: wrap;
            gap: 10px;
        }

        .county-name {
            font-size: 1.1rem;
        }

        .points-panel {
            padding: 1rem;
        }

        .filter-bar {
            border-radius: 20px;
            flex-direction: column;
            gap: 10px;
            padding: 12px;
        }

        .stats-info {
            text-align: center;
        }

        .stations-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('countySearch');
        const countyCards = document.querySelectorAll('.county-accordion-card');
        const visibleCountSpan = document.getElementById('visibleCount');

        function filterCounties() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            countyCards.forEach(card => {
                const countyName = card.getAttribute('data-county-name');
                if (countyName && countyName.includes(searchTerm)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                    const header = card.querySelector('.county-header');
                    const panel = card.querySelector('.points-panel');
                    if (header && header.classList.contains('active')) {
                        header.classList.remove('active');
                        if (panel) panel.classList.remove('show');
                    }
                }
            });

            if (visibleCountSpan) visibleCountSpan.textContent = visibleCount;
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterCounties);
        }

        const countyHeaders = document.querySelectorAll('.county-header');

        countyHeaders.forEach(header => {
            header.addEventListener('click', function(e) {
                e.stopPropagation();
                const targetId = this.getAttribute('data-target');
                const panel = document.getElementById(targetId);
                const isActive = this.classList.contains('active');

                countyHeaders.forEach(h => {
                    if (h !== header && h.classList.contains('active')) {
                        h.classList.remove('active');
                        const otherId = h.getAttribute('data-target');
                        const otherPanel = document.getElementById(otherId);
                        if (otherPanel) otherPanel.classList.remove('show');
                    }
                });

                if (!isActive) {
                    this.classList.add('active');
                    if (panel) panel.classList.add('show');
                } else {
                    this.classList.remove('active');
                    if (panel) panel.classList.remove('show');
                }
            });
        });
    });
</script>
@endpush