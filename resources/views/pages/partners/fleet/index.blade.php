@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')?->user()?->user_type ?? ' ' }}
@endsection
@section('page-title')
    {{ __('Fleet') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.fleet.fleets />
    </div>
@endsection
