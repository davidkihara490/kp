@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('Privacy Policy') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.policy.policy />

</div>
@endsection