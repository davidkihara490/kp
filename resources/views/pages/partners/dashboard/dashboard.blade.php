@extends('pages.partners.layouts.dashboard')
@section('user-type')
{{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
{{ __('Dashboard') }}
@endsection
@section('dashboard-content')
<div>
    <livewire:partners.dashboard.dashboard />
</div>
@endsection