@extends('components.layouts.master')
@section('page-title')
{{ __('Settings') }}
@endsection
@section('page-sub-title')
{{ __('Edit FAQ') }}
@endsection

@section('content')
<div>
    <livewire:admin.settings.faqs.edit-faq :id="$id" />
</div>
@endsection