@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('View Privacy Policy') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.policy.view-policy :id="$id" />

</div>
@endsection