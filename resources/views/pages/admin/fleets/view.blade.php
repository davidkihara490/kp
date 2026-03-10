@extends('components.layouts.master')
@section('page-title')
    {{ __('Fleets') }}
@endsection
@section('page-sub-title')
    {{ __('View Fleet') }}
@endsection

@section('content')
    <div>
        <livewire:admin.fleets.view-fleet :id="$id" />
    </div>
@endsection
