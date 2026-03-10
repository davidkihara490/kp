@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __(key: 'FAQs') }}  
    
@endsection

@section('content')
    <div>
        <livewire:admin.settings.faqs.faqs />
    </div>
@endsection
