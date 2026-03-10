@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('Edit Fleet') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.fleet.edit-fleet :id="$id" />
    </div>
@endsection
