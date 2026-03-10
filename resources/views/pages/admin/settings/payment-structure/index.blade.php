@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Payment Structure') }}  
    
@endsection

@section('content')
    <div>
        <livewire:admin.settings.payment-structure.payment-structures />
    </div>
@endsection
