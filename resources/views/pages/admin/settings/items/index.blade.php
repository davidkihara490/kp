@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Items') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.items.items />
    </div>
@endsection
