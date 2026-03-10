@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('View Pricing') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.pricing.view-pricing  :id="$id"/>
    </div>
@endsection
