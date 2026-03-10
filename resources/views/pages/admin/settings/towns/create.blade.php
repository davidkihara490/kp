@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Create Town') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.towns.create-town/>
    </div>
@endsection
