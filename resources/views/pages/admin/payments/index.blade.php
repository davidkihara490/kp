@extends('components.layouts.master')
@section('page-title')
{{ __('Payments') }}
@endsection
@section('page-sub-title')
{{ __('Payments') }}
@endsection

@section('content')
<div>
    <livewire:admin.payments.payments />

</div>
@endsection