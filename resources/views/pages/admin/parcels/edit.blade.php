@extends('components.layouts.master')
@section('page-title')
    {{ __('Parcels') }}
@endsection
@section('page-sub-title')
    {{ __('Edit Parcel') }}
@endsection

@section('content')
    <div>
        <livewire:admin.parcels.edit-parcel  :id="$id"/>
    </div>
@endsection
