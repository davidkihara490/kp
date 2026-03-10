@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Create Sub Category') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.sub-categories.create-sub-categories />
    </div>
@endsection
