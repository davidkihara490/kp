@extends('components.layouts.master')
@section('page-title')
    {{ __('Drivers') }}
@endsection
@section('page-sub-title')
    {{ __('Create Driver') }}
@endsection

@section('content')
    <div>
        <livewire:admin.drivers.create-driver/>
    </div>
@endsection
