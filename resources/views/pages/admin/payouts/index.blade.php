@extends('components.layouts.master')
@section('page-title')
{{ __('Payouts') }}
@endsection
@section('page-sub-title')
{{ __('Payouts') }}
@endsection

@section('content')
<div>
    <livewire:admin.payouts.payouts />

</div>
@endsection