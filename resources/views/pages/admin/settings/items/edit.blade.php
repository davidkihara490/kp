@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Edit Item') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.items.edit-item  :id="$id"/>
    </div>
@endsection
