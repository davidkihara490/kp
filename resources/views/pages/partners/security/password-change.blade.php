@extends('pages.partners.layouts.dashboard')
@section('user-type')
{{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
{{ __('Password Change') }}
@endsection
@section('dashboard-content')
<div>
    <livewire:partners.security.password-change />
</div>
@endsection