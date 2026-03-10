@extends('pages.partners.layouts.dashboard')
@section('user-type')
    {{ auth()->guard('partner')->user()->user_type }}
@endsection
@section('page-title')
{{ __('View Parcel') }}
@endsection

@section('dashboard-content')
<div>
    <livewire:partners.parcels.view-parcel :id="$id" />
</div>
@endsection