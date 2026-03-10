@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Create FAQ') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.faqs.create-faq />
    </div>
@endsection
