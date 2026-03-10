@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('Edit Pricing') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.pricing.edit-pricing :id="$id" />
</div>
@endsection