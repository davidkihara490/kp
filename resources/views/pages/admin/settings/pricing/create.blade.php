@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Create Pricing') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.pricing.create-pricing/>
    </div>
@endsection
