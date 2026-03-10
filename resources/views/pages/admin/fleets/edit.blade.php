@extends('components.layouts.master')
@section('page-title')
    {{ __('Fleets') }}
@endsection
@section('page-sub-title')
    {{ __('Edit Fleet') }}
@endsection

@section('content')
    <div>
        <livewire:admin.fleets.edit-fleet :id="$id" />
    </div>
@endsection
