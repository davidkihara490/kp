@extends('components.layouts.master')
@section('page-title')
    {{ __('Fleets') }}
@endsection
@section('page-sub-title')
    {{ __('Fleets') }}
@endsection

@section('content')
    <div>
        <livewire:admin.fleets.fleets />
    </div>
@endsection
