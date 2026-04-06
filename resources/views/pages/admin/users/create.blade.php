@extends('components.layouts.master')
@section('page-title')
    {{ __('Create User') }}
@endsection
@section('page-sub-title')
    {{ __('Create User') }}
@endsection

@section('content')
    <div>
        <livewire:admin.users.create-user />
    </div>
@endsection
