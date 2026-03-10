@extends('components.layouts.master')
@section('page-title')
    {{ __('Dashboard') }}
@endsection

@section('content')
    <div>
        <livewire:admin.dashboard.dashboard />
    </div>
@endsection
