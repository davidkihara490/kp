@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')?->user()?->user_type  ?? ''}}
@endsection
@section('page-title')
    {{ __('Transport Rates') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.rates.partner-rates />
    </div>
@endsection
