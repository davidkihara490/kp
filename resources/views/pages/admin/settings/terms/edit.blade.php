@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('Edit Terms and Conditions') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.terms.edit-terms-and-conditions :id="$id"/>

</div>
@endsection