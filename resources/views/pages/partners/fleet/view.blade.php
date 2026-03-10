@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('View Fleet') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.fleet.view-fleet :id="$id" />
    </div>
@endsection
