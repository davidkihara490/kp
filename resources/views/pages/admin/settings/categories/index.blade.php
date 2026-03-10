@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Categories') }}  
    
@endsection

@section('content')
    <div>
        <livewire:admin.settings.categories.categories />
    </div>
@endsection
