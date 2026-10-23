@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')?->user()?->user_type ?? '' }}
@endsection
@section('page-title')
    {{ __('Create Transport Rates') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.rates.create-partner-rates />
    </div>
@endsection
