@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('View Role') }}
@endsection

@section('content')
    <div>
        <livewire:admin.settings.roles-and-permissions.view-roles-and-permission :id="$id" />
    </div>
@endsection