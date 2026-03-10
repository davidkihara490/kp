@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Sub Categories') }}  
    
@endsection

@section('content')
    <div>
        <livewire:admin.settings.sub-categories.sub-categories />
    </div>
@endsection
