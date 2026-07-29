@extends('layouts.app')

@section('title', 'Terms & Conditions | Karibu Parcels')

@section('meta')
    <meta name="description" content="Read the terms and conditions for using Karibu Parcels courier services. Learn about our policies, liabilities, and user agreements.">
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="terms-hero">
        <div class="container text-center">
            <h1><i class="bi bi-file-text-fill me-2"></i> Terms & Conditions</h1>
            <p>Please read these terms carefully before using our courier services. By accessing or using Karibu Parcels, you agree to be bound by these terms.</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <div class="terms-card">
            <div class="last-updated">
                <i class="bi bi-calendar-check"></i> Last Updated: {{ $terms->updated_at->format('Y-M-d') ?? now()->format('Y-M-d') }}
            </div>

            <p>{!! $terms->content ?? '<!-- Terms and conditions content will be displayed here -->' !!}</p>

            <div class="highlight-box mt-4">
                <i class="bi bi-shield-check me-2" style="color: var(--primary-color);"></i>
                By using Karibu Parcels services, you confirm that you have read, understood, and agreed to these Terms and Conditions. Thank you for trusting us with your deliveries.
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .terms-hero {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 60px 0;
            color: white;
            text-align: center;
            margin-bottom: 50px;
        }
        .terms-hero h1 {
            font-weight: 800;
            font-size: 2.8rem;
            margin-bottom: 15px;
        }
        .terms-hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }
        .terms-card {
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            padding: 40px;
            margin-bottom: 50px;
            border: 1px solid var(--border-color);
        }
        .terms-card h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
            padding-left: 18px;
        }
        .terms-card h2:first-of-type {
            margin-top: 0;
        }
        .terms-card p {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--text-light);
            margin-bottom: 1rem;
        }
        .terms-card ul, .terms-card ol {
            margin-bottom: 1.2rem;
            padding-left: 1.5rem;
        }
        .terms-card li {
            margin-bottom: 0.5rem;
            color: var(--text-light);
            line-height: 1.6;
        }
        .terms-card .highlight-box {
            background: var(--primary-light);
            border-left: 4px solid var(--primary-color);
            padding: 20px 25px;
            border-radius: 16px;
            margin: 25px 0;
        }
        .terms-card .last-updated {
            background: var(--light-bg);
            padding: 12px 20px;
            border-radius: 40px;
            display: inline-block;
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 30px;
        }
        .terms-card .last-updated i {
            color: var(--primary-color);
            margin-right: 6px;
        }
        @media (max-width: 768px) {
            .terms-hero h1 { font-size: 2rem; }
            .terms-card { padding: 25px; }
            .terms-card h2 { font-size: 1.4rem; }
        }
    </style>
@endpush