@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('Edit Weight Range') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.weight-ranges.edit-weight-range :id="$id" />
</div>
@endsection