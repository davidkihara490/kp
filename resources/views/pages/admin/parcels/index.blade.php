@extends('components.layouts.master')
@section('page-title')
{{ __('Parcels') }}
@endsection
@section('page-sub-title')
{{ __('Parcels') }}
@endsection

@section('content')
<div>
    <livewire:admin.parcels.parcels />

</div>
@endsection