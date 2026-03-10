@extends('components.layouts.master')
@section('page-title')
    {{ __('Parcels') }}
@endsection
@section('page-sub-title')
    {{ __('Create Parcel') }}
@endsection

@section('content')
    <div>
        <livewire:admin.parcels.create-parcel />
    </div>
@endsection
