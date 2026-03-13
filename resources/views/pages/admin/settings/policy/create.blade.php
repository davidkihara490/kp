@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('Create Privacy Policy') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.policy.create-policy />

</div>
@endsection