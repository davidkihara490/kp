@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('View Zone') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.zones.view-zone :id="$id" />
</div>
@endsection