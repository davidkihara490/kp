@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Pricings') }}  
    
@endsection

@section('content')
    <div>
        <livewire:admin.settings.pricing.pricings />
    </div>
@endsection
