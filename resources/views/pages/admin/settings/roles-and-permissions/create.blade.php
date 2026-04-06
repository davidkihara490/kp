@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Create Role') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.roles-and-permissions.create-roles-and-permission />
    </div>
@endsection
