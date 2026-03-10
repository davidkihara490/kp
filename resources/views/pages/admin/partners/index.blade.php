@extends('components.layouts.master')
@section('page-title')
{{ __('Partners') }}
@endsection
@section('page-sub-title')
{{ __('Partners') }}
@endsection

@section('content')
<div>
    <livewire:admin.partners.partners />

</div>
@endsection