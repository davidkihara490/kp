@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
{{ __('Create Parcel') }}
@endsection

@section('dashboard-content')
<div>
    <livewire:partners.parcels.create-parcel />
</div>
@endsection