@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Towns') }}  
    
@endsection

@section('content')
    <div>
        <livewire:admin.settings.towns.towns />
    </div>
@endsection
