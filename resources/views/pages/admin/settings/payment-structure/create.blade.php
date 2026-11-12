@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('Create Item Payment Structure') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.payment-structure.create-payment-structure />
</div>
@endsection