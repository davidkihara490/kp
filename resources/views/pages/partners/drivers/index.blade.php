@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')?->user()?->user_type  ?? ''}}
@endsection
@section('page-title')
    {{ __('Drivers') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.drivers.drivers />
    </div>
@endsection
