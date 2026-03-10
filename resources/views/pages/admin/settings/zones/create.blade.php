@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('Create Zone') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.zones.create-zone />
</div>
@endsection