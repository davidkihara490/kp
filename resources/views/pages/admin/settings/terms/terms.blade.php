@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('Terms and Conditions') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.terms.terms-and-conditions />

</div>
@endsection