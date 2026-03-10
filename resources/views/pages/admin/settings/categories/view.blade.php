@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('View Category') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.categories.view-category :id="$id" />
    </div>
@endsection
