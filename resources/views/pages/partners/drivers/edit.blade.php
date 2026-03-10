@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
    {{ __('Edit Driver') }}
@endsection
@section('dashboard-content')
    <div>
        <livewire:partners.drivers.edit-driver :id="$id" />
    </div>
@endsection
