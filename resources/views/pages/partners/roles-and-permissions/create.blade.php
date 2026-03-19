@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('Create Role') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.roles-and-permissions.create-roles-and-permission />
    </div>
@endsection
