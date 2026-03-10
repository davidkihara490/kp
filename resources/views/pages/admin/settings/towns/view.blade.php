@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('View Town') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.towns.view-town  :id="$id"/>
    </div>
@endsection
