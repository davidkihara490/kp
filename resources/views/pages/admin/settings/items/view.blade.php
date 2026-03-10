@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('View Item') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.items.view-item  :id="$id"/>
    </div>
@endsection
