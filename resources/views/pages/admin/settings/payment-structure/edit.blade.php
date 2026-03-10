@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('Edit Payment Structure') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.payment-structure.edit-payment-structure :id="$id" />
</div>
@endsection