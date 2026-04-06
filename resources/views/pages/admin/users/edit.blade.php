@extends('components.layouts.master')
@section('page-title')
{{ __('Users') }}
@endsection
@section('page-sub-title')
{{ __('Edit User') }}
@endsection

@section('content')
<div>
    <livewire:admin.users.edit-user :id="$id" />
</div>
@endsection