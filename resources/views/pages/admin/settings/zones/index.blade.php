@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Zones') }}  
    
@endsection

@section('content')
    <div>
        <livewire:admin.settings.zones.zones />
    </div>
@endsection
