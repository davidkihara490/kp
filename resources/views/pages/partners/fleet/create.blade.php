@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('Create Fleet') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.fleet.create-fleet />
    </div>
@endsection
