@extends('layouts.app')

@section('title', 'Privacy Policy | Karibu Parcels')

@section('meta')
    <meta name="description" content="Learn how Karibu Parcels collects, uses, and protects your personal information. Read our privacy policy for complete transparency.">
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="privacy-hero">
        <div class="container text-center">
            <h1><i class="bi bi-shield-lock-fill me-2"></i> Privacy Policy</h1>
            <p>Your privacy is important to us. This policy explains how we collect, use, and protect your personal information when you use Karibu Parcels services.</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <div class="privacy-card">
            <div class="last-updated">
                <i class="bi bi-calendar-check"></i> Last Updated: {{ $policy->updated_at->format('Y-M-d') ?? now()->format('Y-M-d') }}
            </div>

            <p>{!! $policy->content ?? '<!-- Privacy policy content will be displayed here -->' !!}</p>

            <div class="highlight-box mt-4">
                <i class="bi bi-shield-check me-2" style="color: var(--primary-color);"></i>
                By using Karibu Parcels services, you acknowledge that you have read and understood this Privacy Policy. We value your trust and are committed to protecting your personal information.
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .privacy-hero {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 60px 0;
            color: white;
            text-align: center;
            margin-bottom: 50px;
        }
        .privacy-hero h1 {
            font-weight: 800;
            font-size: 2.8rem;
            margin-bottom: 15px;
        }
        .privacy-hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }
        .privacy-card {
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            padding: 40px;
            margin-bottom: 50px;
            border: 1px solid var(--border-color);
        }
        .privacy-card h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
            padding-left: 18px;
        }
        .privacy-card h2:first-of-type {
            margin-top: 0;
        }
        .privacy-card p {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--text-light);
            margin-bottom: 1rem;
        }
        .privacy-card ul, .privacy-card ol {
            margin-bottom: 1.2rem;
            padding-left: 1.5rem;
        }
        .privacy-card li {
            margin-bottom: 0.5rem;
            color: var(--text-light);
            line-height: 1.6;
        }
        .privacy-card .highlight-box {
            background: var(--primary-light);
            border-left: 4px solid var(--primary-color);
            padding: 20px 25px;
            border-radius: 16px;
            margin: 25px 0;
        }
        .privacy-card .last-updated {
            background: var(--light-bg);
            padding: 12px 20px;
            border-radius: 40px;
            display: inline-block;
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 30px;
        }
        .privacy-card .last-updated i {
            color: var(--primary-color);
            margin-right: 6px;
        }
        @media (max-width: 768px) {
            .privacy-hero h1 { font-size: 2rem; }
            .privacy-card { padding: 25px; }
            .privacy-card h2 { font-size: 1.4rem; }
        }
    </style>
@endpush