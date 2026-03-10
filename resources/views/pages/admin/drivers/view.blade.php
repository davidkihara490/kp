@extends('components.layouts.master')
@section('page-title')
    {{ __('Drivers') }}
@endsection
@section('page-sub-title')
    {{ __('View Driver') }}
@endsection

@section('content')
    <div>
        <livewire:admin.drivers.view-driver :id="$id" />
    </div>
@endsection
