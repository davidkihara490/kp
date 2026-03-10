@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Create Item') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.items.create-item />
    </div>
@endsection
