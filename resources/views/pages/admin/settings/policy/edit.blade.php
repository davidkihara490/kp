@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('Edit Privacy Policy') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.policy.edit-policy :id="$id" />

</div>
@endsection