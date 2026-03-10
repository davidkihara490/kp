@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('View Driver') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.drivers.view-driver :id="$id" />
    </div>
@endsection
