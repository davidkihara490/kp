@extends('components.layouts.master')
@section('page-title')
{{ __('Users') }}
@endsection
@section('page-sub-title')
{{ __('View User') }}
@endsection

@section('content')
<div>
    <livewire:admin.users.view-user :id="$id" />
</div>
@endsection