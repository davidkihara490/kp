@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('Edit Profile') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.profile.edit-profile />
    </div>
@endsection
