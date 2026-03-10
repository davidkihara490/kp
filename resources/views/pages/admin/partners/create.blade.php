@extends('components.layouts.master')
@section('page-title')
    {{ __('Partners') }}
@endsection
@section('page-sub-title')
    {{ __('Create Partner') }}
@endsection

@section('content')
    <div>
        <livewire:admin.partners.create-partner />
    </div>
@endsection
