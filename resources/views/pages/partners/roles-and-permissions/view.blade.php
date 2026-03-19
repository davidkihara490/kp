@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('View Role') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.roles-and-permissions.view-roles-and-permission  :id="$id" />
    </div>
@endsection
