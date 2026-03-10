@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('Edit Zone') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.zones.edit-zone :id="$id" />
</div>
@endsection