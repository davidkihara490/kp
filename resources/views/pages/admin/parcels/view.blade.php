@extends('components.layouts.master')
@section('page-title')
    {{ __('Parcels') }}
@endsection
@section('page-sub-title')
    {{ __('View Parcel') }}
@endsection

@section('content')
    <div>
        <livewire:admin.parcels.view-parcel  :id="$id"/>
    </div>
@endsection
