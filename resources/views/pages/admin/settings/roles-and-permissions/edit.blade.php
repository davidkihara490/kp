@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Edit Role') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.roles-and-permissions.edit-roles-and-permission :id="$id"/>
    </div>
@endsection
