@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Edit Category') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.categories.edit-category :id="$id"/>
    </div>
@endsection
