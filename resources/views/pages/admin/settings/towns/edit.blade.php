@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('Edit Town') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.towns.edit-town :id="$id" />
</div>
@endsection