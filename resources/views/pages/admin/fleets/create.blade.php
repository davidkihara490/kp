@extends('components.layouts.master')
@section('page-title')
    {{ __('Fleets') }}
@endsection
@section('page-sub-title')
    {{ __('Create Fleet') }}
@endsection

@section('content')
    <div>
        <livewire:admin.fleets.create-fleet />
    </div>
@endsection
