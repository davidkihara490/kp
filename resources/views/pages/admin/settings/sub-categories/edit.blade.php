@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Edit SubCategory') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.sub-categories.edit-sub-categories :id="$id"/>
    </div>
@endsection
