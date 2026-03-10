@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('View Sub Category') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.sub-categories.view-sub-category :id="$id"/>
    </div>
@endsection
