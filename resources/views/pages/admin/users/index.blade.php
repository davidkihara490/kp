@extends('components.layouts.master')
@section('page-title')
{{ __('Users') }}
@endsection
@section('page-sub-title')
{{ __('Users') }}
@endsection

@section('content')
<div>
    <livewire:admin.users.users />
</div>
@endsection