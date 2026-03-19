@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('Edit Role') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.roles-and-permissions.edit-roles-and-permission :id="$id" />
    </div>
@endsection
