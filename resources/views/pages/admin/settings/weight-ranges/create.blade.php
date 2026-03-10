@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Create Weight Range') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.weight-ranges.create-weight-range/>
    </div>
@endsection
