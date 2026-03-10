@extends('components.layouts.master')
@section('page-title')
    {{ __('Drivers') }}
@endsection
@section('page-sub-title')
    {{ __('Edit Driver') }}
@endsection

@section('content')
    <div>
        <livewire:admin.drivers.edit-driver :id="$id" />
    </div>
@endsection
