@extends('components.layouts.master')
@section('page-title')
    {{ __('Drivers') }}
@endsection
@section('page-sub-title')
    {{ __('Drivers') }}
@endsection

@section('content')
    <div>
        <livewire:admin.drivers.drivers />
    </div>
@endsection
