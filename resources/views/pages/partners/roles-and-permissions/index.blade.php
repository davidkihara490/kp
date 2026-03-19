@extends('pages.partners.layouts.dashboard')
@section('user-type')
{{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
{{ __('Roles') }}
@endsection
@section('dashboard-content')
<div>
    <livewire:partners.roles-and-permissions.roles-and-permissions />
</div>
@endsection