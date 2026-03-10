@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Create Category') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.categories.create-category />
    </div>
@endsection
