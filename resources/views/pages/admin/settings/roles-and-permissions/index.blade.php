@extends('components.layouts.master')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-sub-title')
    {{ __('Roles') }}  
@endsection

@section('content')
    <div>
        <livewire:admin.settings.roles-and-permissions.roles-and-permissions />
    </div>
@endsection
